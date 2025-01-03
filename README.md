
# News Aggregator Backend

A news aggregator website that pulls articles from various sources and serves them to the 
frontend application. Sources used for data collection are:

<ul>
    <li>NewsApi</li>
    <li>The Guardian</li>
    <li> New York Time</li>
</ul>


## Installation

Clone the repository:

```bash
  git clone https://github.com/themitope/news_aggregator.git
  cd news_aggregator
```

Install Dependencies:

```bash
  composer install
```
Configure Environment:

```bash
  cp .env.example .env
```
Generate Application Key:

```bash
  php artisan key:generate
```
Run Migrations:

```bash
  php artisan migrate
```
Start Development Server:

```bash
  php artisan serve
```
    
