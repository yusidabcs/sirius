  const showLoadingModal = function() {
    Swal.fire({
        title: 'Loading',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false
    });
    Swal.showLoading();
  }

  let id_event;
  let date_event;
  let type_event;

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap',
          initialView: 'dayGridMonth',
          height: 'auto',
          contentHeight: 100,
          editable: true,
          showNonCurrentDates: false,
          fixedWeekCount:false,
          headerToolbar: {
            left: 'prev,next today',
            right: 'title'
          },
          buttonIcons: {
            prev: 'fa-chevron-left',
            next: 'fa-chevron-right'
          },
          eventSources: [
            {
              url: '/ajax/interview/interview/calendar-interview',
              method: 'POST',
              extraParams: {
                type: 'location',
              },
              failure: function() {
                alert('there was an error while fetching events!');
              },
              color: '#827819',   
              textColor: '#fff' 
            },
            {
                url: '/ajax/interview/interview/calendar-interview',
                method: 'POST',
                extraParams: {
                  type: 'online',
                },
                failure: function() {
                  alert('there was an error while fetching events!');
                },
                color: '#078225',   
                textColor: '#fff' 
              }
        
          ],
          progressiveEventRendering:false,
          eventContent: function(arg) {
            let title = arg.event.title;
            // console.log(arg.event);
             let new_title = document.createElement('span')
             new_title.setAttribute('id',arg.event.id);
            if (arg.event.extendedProps.type=='online') {
                new_title.innerHTML = "<b><i class='fas fa-laptop'></i> "+title+"</b>";
            } else {
                new_title.innerHTML = "<i class='fas fa-map-marked-alt'></i> "+title;
            }
            
            let arrayOfDomNodes = [ new_title ]
            return { domNodes: arrayOfDomNodes }
          },
          eventClick: function(info) {
            //console.log(JSON.stringify(info.event));
            id_event = info.event.id;
            date_event = info.event.start;
            type_event = info.event.extendedProps.type;

            $('.lds-spinner').show();
            $('#modal_event_content').hide();
            $('#modal_event').modal('show');
            $.ajax({
                url: "/ajax/interview/interview/calendar-event",
                type: 'POST',
                data: {
                    'date': date_event,
                    'type': type_event,
                    'id': id_event
                },
            })
              .done(response => {
                $('#modal_event .modal-title').text(response.type+' - '+response.date);
                $('#modal_event .modal-body').html(response.modal_body);
                $('.lds-spinner').hide();
                $('#modal_event_content').show();
              })
              .fail(response => {
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Connection to Server Failed!'
                  })
                  .then((result) => {
                    if(result.value){
                      $('#modal_event').modal('hide');
                    }
                  });
              })
            /*link = '/ajax/interview/interview/event-interview';
            if(info.event.extendedProps.type=='online') {
              $('#modal_event .modal-title').text('Online Interview');
              $('#'+info.event.id).data('title',info.event.title+" Online interview");
            } else {
              $('#modal_event .modal-title').text('Physical Interview');
              $('#'+info.event.id).data('title',info.event.title+" Physical interview");
            }*/

            //$('#'+info.event.id).tooltip('toggle');
          }
        });
        calendar.render();
      });
$(document).ready(function(){
      $('#address_book_id').materialSelect();
      $('#interviewer_filter').materialSelect();

      $(document).on('click','.btn-interview', function () {
        Swal.fire({
            title: 'Do The Interview',
            text: "You will recorded as the interviewer for this candidate.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if(result.value)
                window.location = $(this).attr('href');
        })
        return false
    })

    $(document).on('click','.btn-set-interview', function () {
      let schedule_id = [$(this).data('schedule-id')];
      let interviewer_id = $(this).data('interviewer-id');
      $("select#address_book_id").find("[selected]").attr("selected", false);
      $("select#address_book_id").find("option[value='"+interviewer_id+"']").attr("selected", true);
      $('#interviewer_modal').modal('show')

      $('#interviewer_form').on('submit',function () {

          Swal.fire({
              title: 'Are you sure?',
              text: "This action will update the selected schedule for selected interviewer.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.value) {
                  let address_book_id = $('#address_book_id').val();
                  showLoadingModal();
                  $.ajax({
                      url: "/ajax/interview/schedule/set_interviewer",
                      type: 'POST',
                      data: {
                          'schedule_id': schedule_id,
                          'address_book_id': address_book_id
                      }
                  })
                      .done(response => {
                          Swal.fire({
                              icon: 'success',
                              title: 'Notification.',
                              text: response.message
                          });
                          //reload table
                            $.ajax({
                              url: "/ajax/interview/interview/calendar-event",
                              type: 'POST',
                              data: {
                                  'date': date_event,
                                  'type': type_event,
                                  'id': id_event
                              },
                          })
                            .done(response => {
                              $('#modal_event .modal-body').html(response.modal_body);
                            })
                            .fail(response => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Connection to Server Failed!'
                                });
                                $('#modal_event .modal-body').html('Connection to Server Failed!');
                            })
                          // end reload table
                          $('#interviewer_modal').modal('hide')
                      })
                      .fail(response => {
                          if(response.status == 400)
                          {
                              text = ''
                              $.each(response.responseJSON.errors, (index,item) => {
                                  text += item;
                              })
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: text
                              });
                          }else{
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Something errors. Please contact admin support!'
                              });
                          }
                      });
              }
          });

          return false;
      })
  })

  $('#interviewer_modal').on('hidden.bs.modal',function(e){
        $('body').addClass('modal-open');
        $('body').attr('style','padding-right: 15px;');
  })
})