/**
 * gallery-data.js
 * 
 * This file acts as the permanent database for your gallery.
 * The system automatically combines this data with any new uploads stored locally in your browser.
 * Whenever you want to save your browser uploads permanently, copy the generated JSON code
 * from the Upload page and paste the new objects into this array.
 */

const galleryData = [
  {
    "id": "demo-001",
    "type": "photo",
    "title": "Cinematic Portrait Collection",
    "date": "2024-03-15",
    "media_url": "pic1.jpeg",
    "description": "A stunning visual exploration of light and shadow.",
    "credits": {
      "director": "",
      "production": "Tejas Media",
      "timeline": "Spring 2024",
      "role": "Lead Photographer",
      "project_desc": "Personal project exploring cinematic lighting techniques.",
      "link": "portfolio.html"
    }
  },
  {
    "id": "demo-002",
    "type": "video",
    "title": "Short Film Teaser: Echoes",
    "date": "2024-02-28",
    "media_url": "https://www.youtube.com/embed/dQw4w9WgXcQ", /* Replace with real video embed URL */
    "description": "The official teaser trailer for my upcoming short film.",
    "credits": {
      "director": "Tejas",
      "production": "Echo Films",
      "timeline": "Winter 2023 - 2024",
      "role": "Director & Cinematographer",
      "project_desc": "A mind-bending thriller exploring the concepts of memory and sound.",
      "link": "https://youtube.com"
    }
  }
];
