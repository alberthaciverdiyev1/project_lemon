package controllers

import (
	"github.com/gofiber/fiber/v2"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
	"projecLemon/models"
	"projecLemon/utils"
)

type AuthController struct {
	DB *gorm.DB
}

func NewAuthController(db *gorm.DB) *AuthController {
	return &AuthController{DB: db}
}

func (ac *AuthController) Login(c *fiber.Ctx) error {
	dbUser := new(models.User)

	authUser := &models.User{
		Email:    c.FormValue("email"),
		Password: c.FormValue("password"),
	}

	if authUser.Email == "" {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"code": fiber.StatusBadRequest, "message": "Email required"})
	}

	if authUser.Password == "" {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"code": fiber.StatusBadRequest, "message": "Password required"})
	}

	ac.DB.Where("email = ?", authUser.Email).First(dbUser)

	if dbUser.ID == 0 {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"message": "User not found"})
	}

	if err := bcrypt.CompareHashAndPassword([]byte(dbUser.Password), []byte(authUser.Password)); err != nil {
		return c.Status(fiber.StatusUnauthorized).JSON(fiber.Map{"code": fiber.StatusUnauthorized, "message": "Incorrect password"})
	}

	token, err := utils.GenerateJwtToken(authUser)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"message": err.Error()})
	}

	c.Cookie(&fiber.Cookie{
		Name:     "token",
		Value:    token,
		HTTPOnly: !c.IsFromLocal(),
		Secure:   !c.IsFromLocal(),
		MaxAge:   3600 * 24 * 7,
	})

	return c.Status(fiber.StatusOK).JSON(fiber.Map{"token": token})
}

func (ac *AuthController) Register(c *fiber.Ctx) error {
	user := models.User{
		Name:     c.FormValue("name"),
		Surname:  c.FormValue("surname"),
		Email:    c.FormValue("email"),
		Password: c.FormValue("password"),
	}

	if user.Email == "" || user.Name == "" || user.Surname == "" || user.Password == "" {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"code":    fiber.StatusBadRequest,
			"message": "All fields are required",
		})
	}

	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), bcrypt.DefaultCost)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"message": err.Error()})
	}
	user.Password = string(hashedPassword)

	ac.DB.Create(&user)

	token, err := utils.GenerateJwtToken(&user)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"message": err.Error()})
	}

	c.Cookie(&fiber.Cookie{
		Name:     "token",
		Value:    token,
		HTTPOnly: !c.IsFromLocal(),
		Secure:   !c.IsFromLocal(),
		MaxAge:   3600 * 24 * 7,
	})

	return c.Status(fiber.StatusOK).JSON(fiber.Map{"token": token})
}
func (ac *AuthController) UpdateProfile(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{"message": "Profile updated"})
}

func (ac *AuthController) ChangePassword(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{"message": "Password changed"})
}
