fetch('https://newsapi.org/v2/top-headlines?country=us&category=general&apiKey=7bc8178ce7b44f09be37ecdfbb37441b')
    .then(response => response.json())
    .then(data => console.log(data));