package routes

import (
	"UserService/models"
	"UserService/utils"
	"github.com/gofiber/fiber/v2"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

func Render(router fiber.Router, db *gorm.DB) {
	auth := router.Group("/auth")

	auth.Get("/login", func(c *fiber.Ctx) error {
		return c.SendString("Login route")
	})

	auth.Post("/register", func(c *fiber.Ctx) error {
		user := models.User{
			Name:     c.FormValue("name"),
			Surname:  c.FormValue("surname"),
			Email:    c.FormValue("email"),
			Password: c.FormValue("password"),
		}

		if user.Email == "" || user.Name == "" || user.Surname == "" {
			return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
				"code":    fiber.StatusBadRequest,
				"message": "Username or Surname required",
			})
		}
		hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), bcrypt.DefaultCost)
		if err != nil {
			return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
				"message": err.Error(),
			})
		}
		user.Password = string(hashedPassword)
		db.Create(&user)
		token, err := utils.GenerateJwtToken(&user)

		if err != nil {
			return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
				"message": err.Error(),
			})
		}
		c.Cookie(&fiber.Cookie{
			Name:     "token",
			Value:    token,
			HTTPOnly: !c.IsFromLocal(),
			Secure:   !c.IsFromLocal(),
			MaxAge:   3600 * 24 * 7, //7 day
		})

		return c.Status(fiber.StatusOK).JSON(fiber.Map{
			"token": token,
		})

	})
}
