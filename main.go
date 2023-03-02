package main

import (
	"github.com/baidelaire/go-restapi-fiber/controllers/bookcontroller"
	"github.com/baidelaire/go-restapi-fiber/controllers/usercontroller"
	"github.com/gofiber/fiber/v2"
	"github.com/baidelaire/go-restapi-fiber/models"
)

func main() {
	models.ConnectDatabase()

	app := fiber.New()

	api := app.Group("/api")
	book := api.Group("/books")
	puzzle := api.Group("/puzzle")

	book.Get("/", bookcontroller.Index)
	// book.Get("/:id", bookcontroller.Show)
	book.Get("/:id", bookcontroller.ShowPuzzle)
	book.Post("/", bookcontroller.Create)
	book.Put("/:id", bookcontroller.Update)
	book.Delete("/:id", bookcontroller.Delete)
	
	puzzle.Get("/" , usercontroller.Show);

	app.Listen(":8000")
}
