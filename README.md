📺 Watchlist Manager API

A Laravel-based REST API for managing a movie watchlist with integration to TMDB (The Movie Database).
Each watchlist item stores a snapshot of movie data fetched from TMDB using an external movie ID.

⚙️ Tech Stack
PHP: 8.2+
Laravel: 11+ / 13.x
Database: MySQL / MariaDB
Auth: Laravel Sanctum
External API: TMDB API
HTTP Client: Laravel HTTP Client
📦 Project Setup
1. Clone repository
   git clone <repository-url>
   cd watchlist-manager-api
2. Install dependencies
   composer install
3. Create environment file
   cp .env.example .env

Generate application key:

php artisan key:generate
4. Configure database

Create database:

CREATE DATABASE watchlist_manager;

Update .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watchlist_manager
DB_USERNAME=root
DB_PASSWORD=
5. Run migrations
   php artisan migrate
6. Configure TMDB API

Create account:

https://www.themoviedb.org/

Generate API token:

https://www.themoviedb.org/settings/api

Add to .env:

TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_ACCESS_TOKEN=your_tmdb_bearer_token_here
7. Start development server
   php artisan serve

API will be available at:

http://127.0.0.1:8000/api
🔐 Authentication

This API uses Laravel Sanctum.

Flow:
Register user
Login → receive token
Use token in requests
Authorization header:
Authorization: Bearer <token>
🚀 API Overview
Auth
POST /api/register
POST /api/login
GET /api/current-user
POST /api/logout
🎬 Watchlist
Add movie
POST /api/watchlist-items
{
"external_id": 603
}
Behavior
Fetches movie data from TMDB
Stores a snapshot of movie data
Prevents duplicate entries per user
Duplicate response
409 Conflict
🧠 Architecture Overview
Snapshot-based design

The system uses a snapshot approach:

TMDB is queried only once (on creation)
Movie data is stored in the local database
Watchlist item contains a frozen snapshot of external data
Advantages
Fast reads (no external API calls on fetch)
Independent from TMDB availability
Stable historical data
Lower external API usage
Disadvantages
Data can become outdated over time
Not real-time synced with TMDB updates
🔄 Future Improvement: Persistent Cache + Sync Strategy

A more advanced architecture can introduce a persistent movie cache table:

🗄 Movies table (central cache)

Instead of storing movie snapshot inside watchlist items:

Create movies table
Store TMDB data once per movie
Link watchlist items to movies

This becomes a shared domain model across users.

🔁 Background synchronization (scheduled job)

A Laravel scheduler / queue worker could:

periodically fetch updates from TMDB
refresh cached movie records
keep internal data aligned with external source

Example tools:

php artisan schedule:run
queued jobs
cron-based execution
⚖️ Live fetch alternative

Another possible approach is always fetching live data from TMDB:

Advantages
Always up-to-date data
No local duplication
Disadvantages
Slower response times (external API per request)
High dependency on TMDB availability
Risk of hitting API rate limits
More expensive in request volume and usage quotas
📌 Summary of strategies
Strategy	Performance	Freshness	Complexity
Snapshot (current)	High	Medium	Low
Persistent cache + sync	High	High	High
Live fetch	Low	Very High	Low
🧠 Domain Design Note

The system intentionally mixes external and internal domains:

External (TMDB) = source of truth at creation time
Internal DB = persisted snapshot / evolving domain model

Over time, the system can evolve toward:

full internal movie domain
background synchronization
or hybrid live-fetch strategy
🧪 Postman

Import provided collection and set environment:

base_url = http://127.0.0.1:8000/api
token = (after login)
🧼 Future Improvements (TODO)
Standardize API response format
Introduce DTO layer for TMDB responses
Add caching layer for TMDB requests
Improve error handling abstraction
Add pagination for watchlist
Add filtering/search endpoints
✅ Summary

This project demonstrates:

Clean Laravel architecture
External API integration (TMDB)
Snapshot-based persistence model
Authentication with Sanctum
Duplicate prevention logic
Clear separation between request data and domain data
Fully testable API via Postman

🧪 Postman Collection

You can import the Postman collection located in the /postman directory:

watchlist-manager-api.collection.json
watchlist-manager-api.environment.json
📦 Collection (raw JSON)
<details> <summary>Click to expand Postman collection</summary>
{
	"info": {
		"_postman_id": "290e1b13-2f9f-46e2-ac4d-d3a747d147b4",
		"name": "watchlist-manager-api",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "38505775"
	},
	"item": [
		{
			"name": "Auth",
			"item": [
				{
					"name": "Register",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Accept",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n  \"name\": \"Test User\",\n  \"email\": \"test@test12.com\",\n  \"password\": \"password\"\n}",
							"options": {
								"raw": {
									"language": "json"
								}
							}
						},
						"url": {
							"raw": "{{base_url}}/register",
							"host": [
								"{{base_url}}"
							],
							"path": [
								"register"
							]
						}
					}
				},
				{
					"name": "Login",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Accept",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n  \"email\": \"test@test.com\",\n  \"password\": \"password\"\n}",
							"options": {
								"raw": {
									"language": "json"
								}
							}
						},
						"url": {
							"raw": "{{base_url}}/login",
							"host": [
								"{{base_url}}"
							],
							"path": [
								"login"
							]
						}
					}
				},
				{
					"name": "Logout",
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{token}}"
								}
							]
						},
						"method": "POST",
						"header": [
							{
								"key": "Accept",
								"value": "application/json"
							}
						],
						"url": {
							"raw": "{{base_url}}/logout",
							"host": [
								"{{base_url}}"
							],
							"path": [
								"logout"
							]
						}
					}
				}
			]
		},
		{
			"name": "User",
			"item": [
				{
					"name": "Current User",
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{token}}"
								}
							]
						},
						"method": "GET",
						"url": {
							"raw": "{{base_url}}/user",
							"host": [
								"{{base_url}}"
							],
							"path": [
								"user"
							]
						}
					}
				}
			]
		},
		{
			"name": "Watchlist",
			"item": [
				{
					"name": "Add Item",
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{token}}"
								}
							]
						},
						"method": "POST",
						"body": {
							"mode": "raw",
							"raw": "{\n  \"external_id\": \"603\"\n}"
						},
						"url": {
							"raw": "{{base_url}}/watchlist-items",
							"host": [
								"{{base_url}}"
							],
							"path": [
								"watchlist-items"
							]
						}
					}
				}
			]
		}
	]
}
</details>
⚙️ Environment Variables
<details> <summary>Click to expand Postman environment</summary>
{
  "base_url": "http://127.0.0.1:8000/api",
  "token": ""
}
</details>
🧠 Notes
base_url controls all API endpoints
token is set after login
All authenticated routes require:
Authorization: Bearer {{token}}
