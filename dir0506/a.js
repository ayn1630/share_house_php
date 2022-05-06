
  window.onload = function() {
    var form = document.createElement('form');
    form.action = 'https://www.google.co.jp/search';
    form.method = 'get';

    var q = document.createElement('input');
    q.value = 'weather';
    q.name = 'q';

    form.appendChild(q);
    document.body.appendChild(form);

    form.submit();
  }


