package utils

import (
	"UserService/models"
	"github.com/golang-jwt/jwt/v5"
	"os"
	"time"
)

func GenerateJwtToken(user *models.User) (string, error) {
	secret := []byte(os.Getenv("SECRET"))
	method := jwt.SigningMethodHS256
	claims := jwt.MapClaims{
		"userId":  user.ID,
		"name":    user.Name,
		"surname": user.Surname,
		"email":   user.Email,
		"exp":     time.Now().Add(time.Hour * 24).Unix(),
	}

	token, err := jwt.NewWithClaims(method, claims).SignedString(secret)
	if err != nil {
		return "", err
	}
	return token, nil
}
