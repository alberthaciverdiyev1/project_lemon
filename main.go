package main

import (
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/log"
	"github.com/joho/godotenv"
	"projecLemon/config"
	"projecLemon/routes"
)

func main() {
	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
	}

	db := config.InitDb()

	app := fiber.New(fiber.Config{
		AppName: "projecLemon",
	})

	routes.RenderRoutes(app, db)

	fmt.Println("Project running on port 3000")

	if err := app.Listen(":3002"); err != nil {
		log.Fatal(err)
	}
}
