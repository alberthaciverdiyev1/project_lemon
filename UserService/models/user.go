package models

import "time"

type User struct {
	ID           uint   `json:"id" gorm:"primary_key"`
	Name         string `json:"name"`
	Surname      string `json:"surname"`
	Email        string `json:"email"`
	Password     string `json:"-"`
	IsActive     bool   `json:"isActive"`
	ProfileImage string `json:"profileImage"`
	BannerImage  string `json:"bannerImage"`

	CreatedAt time.Time `json:"createdAt"`
	UpdatedAt time.Time `json:"updatedAt"`
	DeletedAt time.Time `json:"deletedAt"`
}
