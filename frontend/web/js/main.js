function ProfileUserRatingVote(event, value, caption, id){
    $.ajax({
        url: '/profile/setrating',
        type: 'post',
        data: {
            id: id,
            value: value,
            _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
        },
        beforeSend: function(){},
        complete: function(){},
        success: function (data) {}
    });
}