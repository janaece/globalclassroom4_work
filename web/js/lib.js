function confirmClick(message, dest)
{
    var agree = confirm(message);
    if (agree)
    {
        window.location.href = dest;
    }
    else
    {
        return false;
    }
}