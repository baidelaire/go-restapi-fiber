package models

type Puzzle struct {
	Id int  `gorm:"primaryKey" json:"id"`
	Userid int `gorm:"type:int(11)" json:"userid"`
	Type string `gorm:"type:text" json:"type"`
	Data string `gorm:"type:text" json:"data"`
}