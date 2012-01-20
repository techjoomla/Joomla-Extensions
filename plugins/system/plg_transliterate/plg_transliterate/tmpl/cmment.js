google.load("elements", "1", {
        packages: "transliteration"
      });
  function onLoad() {
    var options = {
        sourceLanguage:
            google.elements.transliteration.LanguageCode.ENGLISH,
        destinationLanguage:
            [google.elements.transliteration.LanguageCode.HINDI],
        shortcutKey: 'ctrl+g',
        transliterationEnabled: true
    };

    var control = new google.elements.transliteration.TransliterationControl(options);


for(var i=1;i<=500;i++)
{
control.makeTransliteratable(['cmmt'+i]);
}

  }
  google.setOnLoadCallback(onLoad);

