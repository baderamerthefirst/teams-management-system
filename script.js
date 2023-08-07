erroro()
function erroro() {
  document.querySelector("form").addEventListener("submit", function (event) {
  var requiredFields = document.querySelectorAll("input:required");
    var isError = false;

    for (var i = 0; i < requiredFields.length; i++) {
      if (requiredFields[i].value.trim() === "") {
        requiredFields[i].classList.add("error");
        document.getElementById(requiredFields[i].id + "-error").textContent =
          "This field is required.";
          

        isError = true;
      } else {
        requiredFields[i].classList.remove("error");
        document.getElementById(requiredFields[i].id + "-error").textContent =
          "";
      }
    }

    if (isError) {
      event.preventDefault();
    }
  });
}
