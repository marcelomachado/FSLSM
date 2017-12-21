function validatePage(questions) {
    var shouldSubmit = true;

    for (var i = 0; i < questions.length; i++) {
        if (!isOneInputChecked(questions[i], "radio")) {
            alert("Por favor, responda todas as questões antes de prosseguir.");
            return false;
        }
    }

    return true;
}

function isOneInputChecked(sel) {
    var inputs = sel.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked) {
            return true;
        }
    }
    return false;
}
