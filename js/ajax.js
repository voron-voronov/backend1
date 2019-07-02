$("#save").click(function() {
  $.ajax({
      type: 'POST',
      url: 'ajax.php',
      data: ({ajax: "save", name: $('#name').val(), surname: $('#surname').val(), age: $('#age').val()}),
      success: function(response){
        if (!response['error']) {
          Swal.fire({
            type: 'success',
            title: 'Заявка отправлена',
            confirmButtonText: 'Понятно',
            text: 'Вы успешно отправили заявку!'
          })
        } else {
          Swal.fire({
            type: 'error',
            title: 'Ошибка',
            confirmButtonText: 'Понятно',
            text: response['error']
          })
        }
      }
    })
})

$("#unload").click(function() {
  $.ajax({
      type: 'POST',
      url: 'ajax.php',
      data: ({ajax: "unload"}),
      success: function(response){
        Swal.fire({
          type: 'success',
          title: 'Запрос выполнен',
          confirmButtonText: 'Понятно',
          html: 'Данные выгружены в <a href="https://docs.google.com/spreadsheets/d/1S3PTdVeeRw1XeT5Jin7TaQU3v2XfQbHT6h6rZcNH8po/edit?usp=sharing">таблицу</a>'
        })
      }
    })
})
