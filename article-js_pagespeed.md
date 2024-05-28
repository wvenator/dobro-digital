# Оптимизация js для PageSpeed Insights
Зачастую js сильно нагружает страницу снижая показатели в PageSpeed Insights, что негативно сказывается на SEO и приводит к низким позициям в поисковых системах. Но что же делать, если **defer** не помогает и нет возможности сократить js, особенно если это скрипты библиотек или рекламных площадок?

Вот несколько простых шагов, которые помогут вам с решением данной проблемы:
## Выявляем ботов
Для начала выявляем ботов с помощью данного кода
```js
var botPattern = "(googlebot/|bot|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis)",
    re = new RegExp(botPattern, "i"),
    userAgent = navigator.userAgent;
const isBot = re.test(userAgent);
```
## Отдаем ботам и реальным посетителям разные скрипты
Скачала напишем функцию для вставки скриптов
```js
function genHtmlFromObject(obj) {
  for (const scriptUrl in obj) {
    const url = obj[scriptUrl];
    let script = document.createElement("script");
    script.src = url;
    document.body.append(script);
  }
}
```
Далее с помощью данной конструции отдаем нужные скрипты
```js
if (isBot) {
  var objUrls = {
    1: "https://unpkg.com/swiper@8/swiper-bundle.min.js",
  };
} else {
  var objUrls = {
    1: "https://unpkg.com/swiper@8/swiper-bundle.min.js",
    2: "https://yandex.ru/ads/system/context.js",
    3: "https://static.pulse.mail.ru/pulse-widget.js",
  };
}
genHtmlFromObject(objUrls);
```
## Ожидаем загрузку скриптов
Для работы с добавленными скриптами нам нужно дождаться их загрузки, для этого у jQuery есть отличная функция **getScript**
```js
$.getScript("https://unpkg.com/swiper@8/swiper-bundle.min.js").done(function (script, textStatus) {
  var frontpageSlider = new Swiper(".front_box-slider", {
    loop: true,
    spaceBetween: 15,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
    },
  });
});
```

## Отложенная загрузка
Для большей эффективности можно отложить загрузку скриптов, сделаем мы это с помощью конструкции ниже
```js
let tired = false;
window.addEventListener("scroll", () => {
  if (tired) return;
  tired = true;
});
```

## Итог
В итоге мы молучаем слудующую конструкцию, которая помогает не только поднять показатели  PageSpeed Insights, но и ускорить загрузку страницы для пользователей.
```js
let tired = false;
window.addEventListener("scroll", () => {
  if (tired) return;
  tired = true;
  if (isBot) {
    var objUrls = {
      1: "https://unpkg.com/swiper@8/swiper-bundle.min.js",
    };
  } else {
    var objUrls = {
      1: "https://unpkg.com/swiper@8/swiper-bundle.min.js",
      2: "https://yandex.ru/ads/system/context.js",
      3: "https://static.pulse.mail.ru/pulse-widget.js",
    };
  }
  genHtmlFromObject(objUrls);
  $.getScript("https://unpkg.com/swiper@8/swiper-bundle.min.js").done(function (script, textStatus) {
    var frontpageSlider = new Swiper(".front_box-slider", {
      loop: true,
      spaceBetween: 15,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
    });
  });
});
```
И помните, что надо скачала максимально оптимизировать ваш код, а уже после прибегать к подобным решениям.
