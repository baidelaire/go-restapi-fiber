package usercontroller

import (
	"net/http"

	"github.com/baidelaire/go-restapi-fiber/models"
	"gorm.io/gorm"

	"github.com/gofiber/fiber/v2"
)


func Show(c *fiber.Ctx) error {

	id := c.Params("id")
	var user models.Users
	if err := models.DB.First(&user, id).Error; err != nil {
		if err == gorm.ErrRecordNotFound {
			return c.Status(http.StatusNotFound).JSON(fiber.Map{
				"message": "Data tidak ditemukan",
			})
		}

		return c.Status(http.StatusInternalServerError).JSON(fiber.Map{
			"message": "Data tidak ditemukan",
		})
	}

	return c.JSON(user)
}

