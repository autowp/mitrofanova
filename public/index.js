var headers = ['Location', 'Content-Type'];

function renderResponse(xhr, element) {
  var result = 'HTTP/1.1 ' + xhr.status + ' ' + xhr.statusText + '\n';

  for (var i = 0; i < headers.length; i++) {
    var value = xhr.getResponseHeader(headers[i]);
    if (typeof value !== 'undefined') {
      result += headers[i] + ': ' + value + '\n';
    }
  }

  result += '\n';

  var contentType = xhr.getResponseHeader('Content-Type');
  var isJSON = contentType && contentType.indexOf('application/json') !== -1;

  if (isJSON) {
    var body = JSON.parse(xhr.response);
    result += '\n' + JSON.stringify(body, null, 2);
  } else {
    result += '\n' + xhr.response;
  }

  element.textContent = result;

  if (xhr.status >= 200 && xhr.status <= 299) {
    element.setAttribute('class', 'alert alert-success');
  } else {
    element.setAttribute('class', 'alert alert-danger');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('form1').addEventListener('submit', function(event) {
    event.preventDefault();

    var input = document.getElementById('input1');
    var result = document.getElementById('alert1');

    result.style.display = 'none';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        if (xhr.status === 201) {
          var body = JSON.parse(xhr.response);

          var loc = window.location;
          var url = loc.protocol + '//' + loc.host + '/' + body.id;

          result.setAttribute('class', 'alert alert-success');
          result.textContent = url;
          result.style.display = 'block';
        } else {
          result.setAttribute('class', 'alert alert-danger');
          result.textContent = xhr.status;
          result.style.display = 'block';
          console.log('Error: ' + xhr.status); // An error occurred during the request.
        }
      }
    };
    xhr.open('POST', '/url');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('url=' + encodeURIComponent(input.value));
  });

  document.getElementById('form2').addEventListener('submit', function(event) {
    event.preventDefault();

    var result = document.getElementById('alert2');

    result.style.display = 'none';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        renderResponse(xhr, result);
        result.style.display = 'block';
      }
    };
    xhr.open('POST', '/number');
    xhr.send();
  });

  document.getElementById('form3').addEventListener('submit', function(event) {
    event.preventDefault();

    var input = document.getElementById('input3');
    var result = document.getElementById('alert3');

    result.style.display = 'none';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        renderResponse(xhr, result);
        result.style.display = 'block';
      }
    };
    xhr.open('GET', '/number/' + input.value);
    xhr.send();
  });
});
