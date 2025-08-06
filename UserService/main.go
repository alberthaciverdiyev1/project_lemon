package main

import (
	"UserService/config"
	"UserService/routes"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/log"
)

func main() {
	db := config.InitDb()

	app := fiber.New(fiber.Config{
		AppName: "UserService",
	})

	routes.Render(app, db)

	fmt.Println("Project running on port 3000")

	if err := app.Listen(":3002"); err != nil {
		log.Fatal(err)
	}
}
