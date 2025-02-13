
// Privilege process


$('input[name=check]').on('click', function(){
    
    let _token = $('meta[name="csrf-token"]').attr('content');

    var user_id = $(this).attr('data-userId')

    var privilege_id = $(this).attr('data-privilegeId')

    var status = $(this).prop('checked')


    let form = new FormData();

    form.append('_token', _token)
    form.append('user_id', user_id);
    form.append('privilege_id', privilege_id);
    form.append('status', status)


    $.ajax({
        url: '/checkPrivilege',
        type : 'POST',
        data : form,
        cache: false,
        contentType: false,
        processData: false,
        success : function(response){
            alert('تم')
        },
        error: function(error){
            $('.errorMessage').html(error.responseJSON.errors);
            console.log(error);
        },
    })

})



// End Privilege Process




// Adding Answers to Question Form


$(document).on('change', '.answer', function(){
    
    var correct = [];

    var wrong = [];



    // var loop_len = wrong_num;

    // var loop_text = 'wrong_';

    // if(correct_num > wrong_num)
    // {
    //     loop_len = correct_num;
    //     loop_text = 'correct_';
    // }

    // for(var i = 1; i <= loop_len; i++)
    // {
    //     correct.push($('#' + loop_text + i).val());

    // }


    for(var i = 1; i <= correct_num; i++)
    {
        if($('#correct_' + i).val() != '')
        {
            correct.push($('#correct_' + i).val());
        }
    }
    
    
    for(var i = 1; i <= wrong_num; i++)
    {
        if($('#wrong_' + i).val() != '')
        {
            wrong.push($('#wrong_' + i).val());
        }
    }



    var correctJson = JSON.stringify(correct);

    var wrongJson = JSON.stringify(wrong);

    $('#correct_answer').val(correctJson);

    $('#wrong_answer').val(wrongJson);

    console.log(correctJson);
    
});



// Ending Adding Answers




// Adding Text Fields to Form

// var correct_num = 1;
// var wrong_num = 1;

// $('.tool_btn').on('click', function(){

//     switch($(this).attr('id'))
//     {
//         case 'plus_correct':
//             correct_num++;
            
//             $('.correct_section').append('<textarea class="form-control text-end answer" rows="4" id="correct_' + correct_num + '" dir="rtl" style="display: inline; border-color: var(--bs-success); width: 70%;"></textarea>');

//             $('#mines_correct').removeClass('disabled');

//             break;

//         case 'mines_correct':
//             $('#correct_' + correct_num).remove();

//             correct_num--;

//             if(correct_num == 1)
//             {
//                 $('#mines_correct').addClass('disabled');
//             }

//             break;

//         case 'plus_wrong':
//             wrong_num++;
            
//             $('.wrong_section').append('<textarea class="form-control text-end answer" rows="4" id="wrong_' + wrong_num + '" dir="rtl" style="display: inline; border-color: var(--bs-danger); width: 70%;"></textarea>');

//             $('#mines_wrong').removeClass('disabled');

//             break;

//         case 'mines_wrong':
//             $('#wrong_' + wrong_num).remove();

//             wrong_num--;

//             if(wrong_num == 1)
//             {
//                 $('#mines_wrong').addClass('disabled');
//             }

//             break;
//     }


// })


// user status

$('.badge-success').on('click', function(){
    var user_id = $(this).attr('data-user');
    $('#userStatus_' + user_id).submit();
    // alert(user_id);
})

$('.badge-danger').on('click', function(){
    var user_id = $(this).attr('data-user');
    $('#userStatus_' + user_id).submit();
    // alert(user_id);
})


// question status

$('.badge-success').on('click', function(){
    var user_id = $(this).attr('data-question');
    $('#questionStatus_' + user_id).submit();
    // alert(user_id);
})

$('.badge-danger').on('click', function(){
    var user_id = $(this).attr('data-question');
    $('#questionStatus_' + user_id).submit();
    // alert(user_id);
})