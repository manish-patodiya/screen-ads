<!DOCTYPE html>
<html>

<head>
    <title>Language Translate Test</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

<body>
    <select class="slct-lang"></select>
    <input type="text" id="lang-field" style="background:white" contenteditable="true" data-text="Enter your text here"
        spellcheck="false" />
    <script type="text/javascript" src="google_jsapi.js"></script>
    <script type="text/javascript" src="langConvert.js"></script>
    <script>
    $(function() {
        googleTranslater(["lang-field"])
    })
    </script>
</body>

</html>