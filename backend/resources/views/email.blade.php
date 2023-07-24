<style type="text/css">
    *{
        font-family: sans-serif;
    }
    div{
        margin: auto;
        width: 500px;
        padding: 1em;
        background-color: #EEE;
        margin-top:2em ;
        border-radius: 20px;
        border: 2px solid CCC;
    }
    div * {
        padding: 1em;
    }
    a{
    color:white;
    font-weight: bold;
    text-decoration: none;
    background-color: green;

    border-radius: 10px;
    float: right;
    }
    a:hover{
        background-color: lightgreen;
    }
    </style>

    <div>

    <h3>Hello {{$invitation->name}} </h3>
    <h4>this is an invitation to join company {{$company}} as a new member</h4>
    <br>
    click on the button to validate and compleate you account <br><br>
    sincerely regards<br>
    {{ $admin }}<br>

    <a href="http://localhost:5173/register?id={{$invitation->id}}">this way</a><br>
    </div>
