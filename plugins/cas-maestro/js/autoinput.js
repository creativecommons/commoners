jQuery(document).ready(function($) {
    
    var MaxTextBoxes = 99;
    $(function () {
        //answer choices
        BindChoiceEvents();
    });

    function BindChoiceEvents() {
        $('table#autoAdd input:text').unbind('keyup');
        //bind appropriate events
        $('table#autoAdd input.istid:last').bind('focusout', BindAnswers);
        var penultimateTextBox = $('table#autoAdd input#txt'+(CurrentTextboxes-1)).bind('keyup', RemoveLastChoice);
    }

    function BindAnswers() {
        var lastTxtBox = $('table#autoAdd input.istid:last');
        var numOfChars = $(lastTxtBox).val().length;
        var options = "<option></option>";
        for (var i=0;i<roles.length;i++)
        {
            options = options + "<option value='" + roles[i] + "'>"+ roles_name[i] +"</option>";
        }
        if (CurrentTextboxes < MaxTextBoxes && numOfChars > 0) {
            //new answer possible, just add a new textbox
            CurrentTextboxes++;
            var newTxtBox = '<tr><td class="prefix"><input type="text" class="istid" name="username['+CurrentTextboxes+']" id="txt' + CurrentTextboxes + '" style="width: 150px;"></input></td><td><select name="role['+CurrentTextboxes+']" style="width: 180px;">'+options+'</select></td></tr>';
            
            var newTxtBox1 = $('<input>').addClass('istid').attr('type', 'text').attr('name','username[' + CurrentTextboxes + ']').width(150).attr('id','txt' + CurrentTextboxes);
            var newTxtBox2 = $('<select>').attr('name','role[' + CurrentTextboxes + ']').width(180).html(options);

            newTxtBox = $('<tr>').append($('<td>').append(newTxtBox1)).append($('<td>').append(newTxtBox2));

            add_new_row('#autoAdd',newTxtBox);

            //rebind
            BindChoiceEvents();
        }
    }

    function RemoveLastChoice() {
        //remove if there's more than one textbox and penultimate textbox is empty
        if (CurrentTextboxes > 1) {
            var penultimateTxtBox = $('table#autoAdd input#txt'+(CurrentTextboxes-1));
            var numOfChars = $(penultimateTxtBox).val().length;

            if (numOfChars == 0) { 
                $('table#autoAdd tr:last').remove();
                CurrentTextboxes--;
                //rebind
                BindChoiceEvents();
            }
        }
    }

    function add_new_row(table,rowcontent){
        if ($(table).length>0){
            if ($(table+' > tbody').length==0) $(table).append('<tbody />');
            ($(table+' > tr').length>0)?$(table).children('tbody:last').children('tr:last').append(rowcontent):$(table).children('tbody:last').append(rowcontent);
            rowcontent.find('select').select2({placeholder: casmaestro.choose_role});
        }
    }
});