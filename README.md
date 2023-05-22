## Details

**Note:** There is much more to the app architecture that be made better 
but due to time investment it was getting expensive. 
If there is a need for the more indepth insight we can discuss what possible can be done better. 
Eg. There are a lot `\App\Services\ArticleService` class can be made more efficient etc. For the API endpoints security is skipped but can be added.

#### Folders of interest would be
* `app/Console/Commands`
* `app/Dto/News`
* `app/Http/Controllers`
* `app/Services/Importers`
* `app/Services`

#### Requirements

* User authentication and registration: Users should be able to create an account and
log in to the website to save their preferences and settings.
  * <span style="color:green">The API is setup for it as it comes with laravel default settings.</span>
  * <span style="color:red">Unable to finish the frontend implementation of it.</span>
    
* Article search and filtering: Users should be able to search for articles by keyword
   and filter the results by date, category, and source.
    * <span style="color:green">The API endpoints is working and search for all articles.</span>
    * <span style="color:green">`category` is renamed as `source` filter, as this is the more aligned name with the `newsapi.org` data/API responses.<span>
    * <span style="color:green">`source` is renamed as `origin` filter.</span>

* Personalized news feed: Users should be able to customize their news feed by
   selecting their preferred sources, categories, and authors.
    * <span style="color:red">This implementation is skipped due to the time availiblity.</span>
* Mobile-responsive design: The website should be optimized for viewing on mobile
   devices.
    * <span style="color:green">The design is basic bootstrap and it is responsive.</span>

# [Frontend](https://github.com/mubasharkk/innoscripta-frontend)
* The app is very basic and simple displaying the list of articles and having a search filters by `keyword` (title only), `source`, `dates` , `author`
* Setup is simple
    * `npm install`
    * `npm run dev`

**Tech Note:**  The react-js frontend is build on using `nextjs` framework. I don't have extensive expertise in `nextjs` so I have to look and reuse/modify some code from other sources available online.
The app has very basic setup and functionality and there is a lot of refactor special using proper SSR and its components.  

The backend API data fetching can be added to config file, but currently they are using the domain `http://localhost:8080` and that is recommnded for the backend app.

A template with Next.js 13 app dir, Contentlayer, Tailwind CSS and dark mode.

https://next-contentlayer.vercel.app

## Challenge Guidelines

* All the task/challenge guidelines are observer. 
  * 2 apps frontend / backend.
  * Dockerize container for backend app using laravel/sail.
  * Suitable and simple design is used for the frontend.
  * Only 2 data sources are used to import articles. 
    * [newsapi.org](https://newsapi.org/)
    * [The Guardian](https://open-platform.theguardian.com/documentation/)
## Basic setup

### Running docker

```
$ composer install
$ ./vendor/bin/sail up -d 
```

### DB Import

To setup the app with initialized data. 

```
$ ./vendor/bin/sail artisan db:seed

# Run queue worker after setting up to fetch articles

$ ./vendor/bin/sail artisan queue:work --tries=3 --queue=source-news-item
```

### Available artisan commands 

**Import news categories from APIs**

```
$ ./vendor/bin/sail artisan import:news-items {origin} {source} {--domain=} {--page=1} {--language=}
```
**Import category news from APIs**

```
$ ./vendor/bin/sail artisan import:news-sources {origin} {--category=} {--lang=en} {--country=us}

```
**Queue up jobs for to fetch news articles for each category, per origin**

```
$ ./vendor/bin/sail artisan import:news-items-from-sources {origin}

```

### User

* Email: demo@demo.com
* Password: demo123

## Endpoints

### Articles

```http
GET /api/articles
```

Get list of all articles sorted by `published_at` field.

| Parameter  | Type     | Default | Description                                |
|:-----------|:---------|:--------|:-------------------------------------------|
| `origin`   | `string` | `null`  | Origin API of news imported.               |
| `locale`   | `string` | `en`    | ['de', 'en']                               |
| `source`   | `string` | `null`  | Source/category of the article             |
| `author`   | `string` | `null`  | Name of the author                         |
| `keyword`  | `string` | `null`  | Keyword to search title for                |
| `fromDate` | `string` | `null`  | Articles have published date after         |
| `tillDate` | `string` | `null`  | Articles have published date before        |
| `page`     | `int`    | `1`     | Current page number                        |

### Response

```json
{
    "data": [
        {
            "id": 1,
            "origin": "the-guardian",
            "source": {
                "slug": "the-guardian:australia-news",
                "title": "Australia news",
                "description": null,
                "category": null,
                "country": {
                    "code": "US",
                    "name": "United States"
                },
                "language": {
                    "code": "en",
                    "name": "English",
                    "name_native": "English"
                },
                "url": "https://www.theguardian.com/australia-news"
            },
            "title": "Australia politics live: Chalmers promises action on consultants’ ‘inexcusable’ use of government secrets; BNPL reforms to be announced",
            "teaser": "Follow live",
            "author": "Amy Remeikis & Caitlin Cassidy & Stephanie Convery",
            "publishedAt": "2023-05-21 23:39:50",
            "url": "https://www.theguardian.com/p/z3aqq",
            "image": "https://media.guim.co.uk/e22c007422286955b61125a5d138d4eb17ecff49/1004_67_4230_2540/500.jpg"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 308,
    }
}
``` 

### Sources

```http
GET /api/sources
```
Get list of all news sources

| Parameter | Type     | Default | Description                    |
|:----------|:---------|:--------|:-------------------------------|
| `origin`  | `string` | `null`  | Origin API of news imported.   |
| `locale`  | `string` | `en`    | ['de', 'en']                   |
| `country` | `string` | `en`    | ['de', 'en']                   |
| `sources` | `string` | `null`  | Source/category of the article |
| `page`    | `int`    | `1`     | Current page number            |

### Response

```json
{
    "data": [
        {
            "id": 1,
            "origin": "the-guardian",
            "source": {
                "slug": "the-guardian:australia-news",
                "title": "Australia news",
                "description": null,
                "category": null,
                "country": {
                    "code": "US",
                    "name": "United States"
                },
                "language": {
                    "code": "en",
                    "name": "English",
                    "name_native": "English"
                },
                "url": "https://www.theguardian.com/australia-news"
            },
            "title": "Australia politics live: Chalmers promises action on consultants’ ‘inexcusable’ use of government secrets; BNPL reforms to be announced",
            "teaser": "Follow live",
            "author": "Amy Remeikis & Caitlin Cassidy & Stephanie Convery",
            "publishedAt": "2023-05-21 23:39:50",
            "url": "https://www.theguardian.com/p/z3aqq",
            "image": "https://media.guim.co.uk/e22c007422286955b61125a5d138d4eb17ecff49/1004_67_4230_2540/500.jpg"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 308
    }
}
``` 

### Running Tests

There are only basic API endpoints status code test added for each endpoint. 

```cmd
$ ./vendor/bin/sail pest 
```
