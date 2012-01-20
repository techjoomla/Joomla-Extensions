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
 
setTimeout(arguments.callee, 100); 

for(var i=0;i<=30;i++)
{	
control.makeTransliteratable(['value'+i]);
}
   }
      google.setOnLoadCallback(onLoad);

