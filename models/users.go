package models

type Users struct {
	Id          int64  `gorm:"primaryKey" json:"id"`
	Email       string `gorm:"type:text" json:"email"`
	Username 	string `gorm:"type:text" json:"username"`
	Password    string `gorm:"type:text" json:"password"`
	Skey 		string `gorm:"type:text" json:"skey"`
	Is_verify 	int	   `gorm:"type:int(11)" json:"is_verify"`
}