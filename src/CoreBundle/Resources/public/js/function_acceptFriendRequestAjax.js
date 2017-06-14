function acceptFriendRequestAjax(idBoug)
{
    $.ajax({
        url: "/HDB/web/app_dev.php/friends/accept-friend-request",
        method:"post",
        data: {idBoug : idBoug}
    })
    .done(function(data)
    {
        if(data['friendAdded'] == 'success')
        {
            $('.friendRequest[idBoug="'+idBoug+'"] button').after('Vous êtes à présent amis').remove();
            if($('#add-friends-zone').length != 0)
            {
                $('#add-friends-zone .boug-requesting-user[id-boug="'+idBoug+'"] button').after('Vous êtes à présent amis').remove();
            }
        }
    });
}