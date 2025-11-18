# Todo管理アプリ（Laravel × Vue3）

Laravel 11 と Vue 3 を使用して開発中の **ToDo管理アプリ** です。  
個人開発練習として制作しています。  

---

## 🌟 使用技術

- フロントエンド : Vue 3 / Pinia / Vue Router / Axios / Bootstrap 5
- バックエンド : Laravel 11 / Laravel Sanctum / Laravel Breeze
- データベース : MySQL
- API : REST API（/api/progress, /api/setting 等）
- 認証 : Google ログイン（OAuth）＋ Laravel Breeze
- デザイン : Figma（UI カンプ）
- ER 図 : dbdiagram.io

---

## 🔧 主な機能

-  ユーザー登録・ログイン（Googleログイン対応）  
-  ToDo一覧・追加・編集・削除（REST API連携）  
-  ユーザー設定編集->ユーザー編集・パスワード変更・カテゴリー追加・タスク追加（/api/setting）
-  管理者画面（Admin） 

---

## 📊 システム構成

- **バックエンド**：Laravel 11  
  - 認証：Sanctum / Breeze  
  - APIルート：`/api/progress`, `/api/setting`  
- **フロントエンド**：Vue 3  
  - 状態管理：Pinia  
  - ルーティング：Vue Router  
  - 通信：Axios  
  - UI：Bootstrap 5  
- **DB**：MySQL  

---

## 🧠 設計資料

- [Figma デザインカンプ](https://www.figma.com/design/eNmObdfLUhu9Jdh2Ksc81e/laravel-vue-todo?node-id=0-1&t=QP6r1xv41L927Csc-1)  
- [ER 図（dbdiagram.io）](https://dbdiagram.io/d/ToDoLists-68f8f0a5357668b7321d7f0b)

---

## 🖼 画面イメージ

- [ログイン画面](images/Login.png)
- [タスク画面（進行タスク）](images/ProgressTask.png)
- [タスク画面（完了タスク）](images/CompletedTask.png)
- [タスク編集画面](images/EditTask.png)
- [設定画面](images/Setting.png)
