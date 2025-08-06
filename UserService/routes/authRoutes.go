package routes

import (
	"UserService/controllers"
	"UserService/middlewares"
	"github.com/gofiber/fiber/v2"
	"gorm.io/gorm"
)

func RenderRoutes(app *fiber.App, db *gorm.DB) {
	authController := controllers.NewAuthController(db)

	// PUBLIC ROUTES
	auth := app.Group("/auth")
	auth.Post("/login", authController.Login)
	auth.Post("/register", authController.Register)

	// PROTECTED ROUTES
	protected := app.Group("/auth", middlewares.AuthMiddleware(db))
	protected.Put("/update", authController.UpdateProfile)
	protected.Post("/change-password", authController.ChangePassword)
}
