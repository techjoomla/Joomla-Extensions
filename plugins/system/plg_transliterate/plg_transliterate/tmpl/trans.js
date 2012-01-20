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


// profile page
control.makeTransliteratable(['check']);
control.makeTransliteratable(['title']);
control.makeTransliteratable(['location']);
control.makeTransliteratable(['keyword']);
control.makeTransliteratable(['srch']);
control.makeTransliteratable(['bookmarks-message']);
control.makeTransliteratable(['field6']);
    control.makeTransliteratable(['field7']);
	control.makeTransliteratable(['field8']);
	control.makeTransliteratable(['field9']);
   	control.makeTransliteratable(['field10']);
   
}
google.setOnLoadCallback(onLoad);






