## PHP 留言板
[DEMO](https://nijigamieta.tw/project/message-board/)

![留言板](https://user-images.githubusercontent.com/20063249/123838411-8fe0b480-d93e-11eb-976c-30a5d51612aa.gif)

- 測試請用以下帳號密碼

  |身份類型|帳號|密碼|
  |----|----|----|
  |管理員|ADMIN|ADMIN|
  |一般使用者|normal|normal|
  |被停權使用者|BANNED|BANNED|
  
### 留言頁面
 <img width="600" alt="截圖 2021-06-30 上午1 13 02" src="https://user-images.githubusercontent.com/20063249/123839934-5315bd00-d940-11eb-8fc0-811d94faa7e2.png">

- 一般使用者登入後可以編輯自己的暱稱
- 一般使用者登入後可以送出留言
- 一般使用者登入後可以編輯自己的留言
- 一般使用者登入後可以刪除自己的留言


### 後台頁面
<img width="618" alt="截圖 2021-06-30 上午1 15 19" src="https://user-images.githubusercontent.com/20063249/123840205-a425b100-d940-11eb-9c55-5014e4b26eca.png">

- 管理員身份可以進入後台調整使用者權限
- 管理員身份可以刪除編輯所有人的留言

### 使用技術
- PHP
- 部署到 aws EC2
  - 利用 LAMP 在 EC2 主機上架站，作業系統安裝 unbuntu，建立 Apache 網頁伺服器、MySQL 資料庫
  - 網域使用在 [Gandi.net](https://www.gandi.net/zh-Hant)購買的域名
