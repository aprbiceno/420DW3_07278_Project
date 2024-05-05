function clearForm() {
    $('#usergroup-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("usergroup-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#usergroup-form")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length > 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}

function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("id", $("#usergroup-id").val());
    formData.set("name", $("#usergroup-name").val());
    formData.set("description", $("#usergroup-description").val());
    formData.set("dateCreated", $("#usergroup-date-created").val());
    formData.set("dateLastModified", $("#usergroup-date-last-modified").val());
    $(".user-usergroups").each((index, inputElem) => {
        console.log(inputElem);
        formData.set(inputElem.name, $(inputElem).prop("checked"));
    });
    console.log(Object.fromEntries(formData));
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#usergroup-id").val(entityObject.id);
    }
    if ('name' in entityObject) {
        $("#usergroup-name").val(entityObject.title);
    }
    if ('description' in entityObject) {
        $("#usergroup-description").val(entityObject.description);
    }
    if ('dateCreated' in entityObject) {
        $("#author-date-created").val(entityObject.dateCreated);
    }
    if ('dateLastModified' in entityObject) {
        $("#author-date-last-modified").val(entityObject.dateLastModified);
    }
    
    // uncheck all authors
    $(".user-usergroups").each((index, inputElem) => {
        $(inputElem).prop("checked", false)
    });
    
    if ('users' in entityObject) {
        if (typeof entityObject.users === "object") {
            console.log(Object.keys(entityObject.users));
            Object.keys(entityObject.users).forEach((value) => {
                $(`#user-usergroups-${value}`).prop("checked", true);
            });
        }
    }
    
    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

function displayResponseError(responseErrorObject) {
    let errorContainer = $(".error-display");
    let classnameContainer = $("#error-class");
    let messageContainer = $("#error-message");
    let previousContainer = $("#error-previous");
    let stacktraceContainer = $("#error-stacktrace");
    if ('exception' in responseErrorObject && typeof responseErrorObject.exception === "object") {
        let exception = responseErrorObject.exception;
        classnameContainer.empty();
        messageContainer.empty();
        previousContainer.empty();
        if ('exceptionClass' in exception) {
            classnameContainer.html(exception.exceptionClass);
        }
        if ('message' in exception) {
            messageContainer.html(exception.message);
        }
        while ('previous' in exception && typeof exception.previous === "object") {
            exception = exception.previous;
            if ('exceptionClass' in exception && 'message' in exception) {
                previousContainer.append(`Caused by: ${exception.exceptionClass}: ${exception.message}<br/>`);
            }
        }
    }
    stacktraceContainer.empty();
    if ('stacktrace' in responseErrorObject) {
        stacktraceContainer.html(responseErrorObject.stacktrace.replace(/\r\n/g, '\n'));
    }
    errorContainer.slideToggle().delay(5000).slideToggle();
    
}

function loadUsergroup() {
    let selectedRecordId = document.getElementById("usergroup-selector").value;
    
    const options = {
        "url": `${API_USERGROUP_URL}?usergroupId=${selectedRecordId}`,
        "method": "get",
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function createUsergroup() {
    const options = {
        "url": `${API_USERGROUP_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         if ('name' in data) {
             let selector = document.getElementById("usergroup-selector");
             let newOptionElement = document.createElement("option");
             newOptionElement.value = data.id;
             newOptionElement.innerHTML = `${data.name}`;
             selector.appendChild(newOptionElement);
             selector.value = data.id;
         }
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function updateUsergroup() {
    const options = {
        "url": `${API_USERGROUP_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         // Replace the text in the selector with the updated values
         let formIdValue = document.getElementById("usergroup-id").value;
         if ('name' in data) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("usergroup-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                 elem.innerHTML = `${data.name}`;
             });
         }
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function deleteUsergroup() {
    const options = {
        "url": `${API_USERGROUP_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         let formIdValue = document.getElementById("usergroup-id").value;
         if (formIdValue) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("usergroup-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => elem.remove());
             selector.value = "";
         }
         clearForm();
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

document.getElementById("view-instance-button").onclick = loadUsergroup;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createUsergroup;
document.getElementById("update-button").onclick = updateUsergroup;
document.getElementById("delete-button").onclick = deleteUsergroup;
$("#usergroup-form").on("change", ":input", updateClearButtonState);
