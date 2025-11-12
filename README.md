# Todo管理アプリ（Laravel × Vue3）

Laravel 11 と Vue 3 を使用して開発中の **ToDo管理アプリ** です。  
個人開発練習として制作しています。  
現在は **管理者画面（Admin）機能** の実装を進めています。

---

## 🌟 使用技術

- フロントエンド : Vue 3 / Pinia / Vue Router / Axios / Bootstrap 5
- バックエンド : Laravel 11 / Laravel Sanctum / Laravel Breeze
- データベース : MySQL
- API : REST API（/api/tasks, /api/setting）
- 認証 : Google ログイン（OAuth）＋ Laravel Breeze
- デザイン : Figma（UI カンプ）
- ER 図 : dbdiagram.io

---

## 🔧 主な機能

-  ユーザー登録・ログイン（Googleログイン対応）  
-  ToDo一覧・追加・編集・削除（REST API連携）  
-  ユーザー設定編集（/api/setting）  
-  管理者画面（Admin）実装中  
-  タスクのタグ・カテゴリー機能（予定）  

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

- [ログイン画面](login.png)
- [ToDo画面](todo.png)
- [設定画面](setting.png)

---

## 🚀 セットアップ方法

- 追加予定
