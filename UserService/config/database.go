package config

import (
	"UserService/models"
	"github.com/gofiber/fiber/v2/log"
	"gorm.io/gorm"
)
import "gorm.io/driver/sqlite"

func InitDb() *gorm.DB {
	db, err := gorm.Open(sqlite.Open("database.db"))
	if err != nil {
		log.Fatal("Error connecting to database")
	}

	db.AutoMigrate(&models.User{})

	return db
}
