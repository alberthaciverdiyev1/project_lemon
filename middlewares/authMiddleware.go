package middlewares

import (
	"errors"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/log"
	"github.com/golang-jwt/jwt/v5"
	"gorm.io/gorm"
	"os"
	"projecLemon/models"
	"strings"
)

func AuthMiddleware(db *gorm.DB) fiber.Handler {
	return func(c *fiber.Ctx) error {
		cookieToken := c.Cookies("token")
		var strToken string
		if cookieToken != "" {
			log.Warn("Token is valid")
			strToken = cookieToken

		} else {
			log.Warn("Token not found")
			authHeader := c.Get("Authorization")

			if authHeader == "" {
				return c.Status(fiber.StatusUnauthorized).JSON(fiber.Map{
					"code": fiber.StatusUnauthorized,
					"msg":  "Unauthorized",
				})
			}

			tokenParts := strings.Split(authHeader, " ")
			if len(tokenParts) != 2 && tokenParts[0] != "Bearer" {
				return c.Status(fiber.StatusUnauthorized).JSON(fiber.Map{
					"code": fiber.StatusUnauthorized,
					"msg":  "Unauthorized",
				})
			}

			strToken = tokenParts[1]

			secret := []byte(os.Getenv("SECRET"))
			token, err := jwt.Parse(strToken, func(t *jwt.Token) (interface{}, error) {

				if t.Method.Alg() != jwt.SigningMethodHS256.Alg() {
					return nil, fmt.Errorf("unexpected signing method: %v", t.Header["alg"])
				}
				return secret, nil
			})

			if err != nil || !token.Valid {
				c.ClearCookie()
				return c.Status(fiber.StatusUnauthorized).JSON(fiber.Map{
					"code": fiber.StatusUnauthorized,
					"msg":  "Unauthorized",
				})
			}

			userId := token.Claims.(jwt.MapClaims)["userId"].(string)

			if err := db.Model(&models.User{}).Where("id = ?", userId).Error; errors.Is(err, gorm.ErrRecordNotFound) {
				c.ClearCookie()
				return c.Status(fiber.StatusUnauthorized).JSON(fiber.Map{
					"code": fiber.StatusUnauthorized,
					"msg":  "Unauthorized",
				})
			}

			c.Locals("userId", userId)
		}
		return c.Next()
	}
}
