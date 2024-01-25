let currentLang = "ENGLISH";
$(function () {
	let langList = [
		"KANNADA",
		"TELUGU",
		"HINDI",
		"URDU",
		"TAMIL",
		"BENGALI",
		"GUJARATI",
		"ENGLISH",
		// "HUNGARIAN",
		// "AFRIKAANS",
		// "IRISH",
		// "ALBANIAN",
		// "Italian ",
		// "Arabic",
		// "Japanese",
		// "Azerbaijani",
		// "Basque",
		// "Korean",
		// "Latin",
		// "Belarusian",
		// "Latvian",
		// "Bulgarian",
		// "Lithuanian",
		// "Catalan",
		// "Macedonian",
		// "Chinese Simplified",
		// "Malay",
		// "Chinese Traditional",
		// "Maltese",
		// "Croatian",
		// "Norwegian",
		// "Czech",
		// "Persian",
		// "Danish",
		// "Polish",
		// "Dutch",
		// "Portuguese",
		// "Romanian",
		// "Esperanto",
		// "Russian",
		// "Estonian",
		// "Serbian",
		// "Filipino",
		// "Slovak",
		// "Finnish",
		// "Slovenian",
		// "French",
		// "Spanish",
		// "Galician",
		// "Swahili",
		// "Georgian",
		// "Swedish",
		// "German",
		// "Greek",
		// "Thai",
		// "Haitian Creole",
		// "Turkish",
		// "Hebrew",
		// "Ukrainian",
		// "Vietnamese",
		// "Icelandic",
		// "Welsh",
		// "Indonesian",
		// "Yiddish",
	].sort((a, b) => {
		return b < a ? 1 : -1;
	});
	function getQueryStrings() {
		var assoc = {};
		var decode = function (s) {
			return decodeURIComponent(s.replace(/\+/g, " "));
		};
		var queryString = location.search.substring(1);
		var keyValues = queryString.split("&");

		for (var i in keyValues) {
			var key = keyValues[i].split("=");
			if (key.length > 1) {
				assoc[decode(key[0]).toLowerCase()] = decode(key[1]);
			}
		}

		return assoc;
	}

	function createQueryString(queryDict) {
		var queryStringBits = [];
		for (var key in queryDict) {
			if (queryDict.hasOwnProperty(key)) {
				queryStringBits.push(key + "=" + queryDict[key]);
			}
		}
		return queryStringBits.length > 0 ? "?" + queryStringBits.join("&") : "";
	}

	$(".slct-lang").change(function () {
		var queryString = getQueryStrings();
		queryString["lang"] = $(".slct-lang").val();

		window.location.href =
			window.location.protocol +
			"//" +
			window.location.host +
			window.location.pathname +
			createQueryString(queryString);
	});

	var queryString = getQueryStrings();
	const langId = queryString.lang || "ENGLISH";
	langList.map((l) => {
		$(".slct-lang").append(
			`<option value="${l}" ${langId == l ? `selected` : ``}>${l}</option>`
		);
		if (langId == l) {
			currentLang = `${l}`;
		}
	});
	google.load("elements", "1", {
		packages: "transliteration",
	});
});
function googleTranslater(fields) {
	function onLoad() {
		var control = new google.elements.transliteration.TransliterationControl({
			sourceLanguage: "en",
			destinationLanguage: [
				google.elements.transliteration.LanguageCode[currentLang],
			],
			shortcutKey: "ctrl+g",
			transliterationEnabled: true,
		});
		control.makeTransliteratable(fields);
	}
	if (currentLang != "ENGLISH") google.setOnLoadCallback(onLoad);
}
