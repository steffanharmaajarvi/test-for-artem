start:
	cp .env.example .env
	docker-compose up -d --build
	docker-compose exec -it api composer install
	docker-compose exec -it api php artisan serve
