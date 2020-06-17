
function Notify(type, text, id = false, timeout = 3000) {

  let noty = new Noty({
    timeout: timeout,
    type: type,
    text: text,
    theme: 'mint',
    closeWith: ['click'],
    visibilityControl: true,
    layout: 'topRight',
    sounds:{
      volume: 1,
      conditions: ['docVisible', 'docHidden']
    },
    id: id,
    animation: {
      open: 'animated fadeInRight', // Animate.css class names
      close: 'animated fadeOutRight' // Animate.css class names
    }

  });

  noty.hasSound= true;

  let audioElement = document.createElement('audio');
  audioElement.setAttribute('src', 'assets/audio/intuition.mp3');


  switch (type) {
    case 'error':
    noty.options.sounds.sources= ['assets/audio/glitch-in-the-matrix.mp3'];
    break;
    case 'warning':
    noty.options.sounds.sources= ['assets/audio/to-the-point.mp3'];
    break;
    case 'info':
    noty.options.sounds.sources= ['assets/audio/eventually.mp3'];
    break;
    case 'success':
    noty.options.sounds.sources= ['assets/audio/anxious.mp3'];
    break;

  }

  if (timeout == false) {

    noty.on('onShow', function () {
      this.barDom.classList.add('fixed_notify');
    });

    switch (type) {
      case 'error':
      noty.on('afterShow', function () {
        this.barDom.classList.add('animated');
        this.barDom.classList.add('infinite');
        this.barDom.classList.add('pulse-custom');
      });

      noty.on('onClose',function(){
        this.barDom.classList.remove('pulse-custom');
      });
      break;

      case 'warning':
      noty.on('afterShow', function () {
        this.barDom.classList.add('animated');
        this.barDom.classList.add('infinite');
        this.barDom.classList.add('shake-custom');
      });

      noty.on('onClose',function(){
        this.barDom.classList.remove('shake-custom');
      });
      break;

      case 'info':
      noty.on('afterShow', function () {
        this.barDom.classList.add('animated');
        this.barDom.classList.add('infinite');
        this.barDom.classList.add('flash-custom');
      });

      noty.on('onClose',function(){
        this.barDom.classList.remove('flash-custom');
      });

      break;
    }

    noty.on('onClose', function () {
      this.barDom.classList.remove('infinite');
    });

  }

  noty.on('onClose',function(){

    audioElement.play();
  });

  noty.show();

}
