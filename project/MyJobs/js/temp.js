// ���������� �������������� �������
function BubbleStop() 
{
    event = event || window.event // �����-���������
     
    if ( event.stopPropagation ) 
        // ������� ��������� W3C:
        event.stopPropagation();
else 
    {
        // ������� Internet Explorer:
        event.cancelBubble = true;
    }
}    
