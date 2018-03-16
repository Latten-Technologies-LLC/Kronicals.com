<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <h1 id="addition">0</h1>
</div>

<script src="{{ asset('js/jquery.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
<script>
    var socket = io('http://localhost:3000');
    socket.on("test-channel:App\\Events\\NewUserSignup", function(message) {
        console.log('here');
        $('#addition').text(parseInt($('#addition').text()) + parseInt(message.data.addition));
    });
</script>