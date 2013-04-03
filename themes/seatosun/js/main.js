;(function($) {
    $(function() {
        // Add keyboard navigation support to nav menu
        var currentMenuLink;
        var retainOrRelease = 0;
        
        $('.sub-menu-container > a, .sub-menu a').focusin(function(event) {
            retainOrRelease++;
            
            if (retainOrRelease == 1) {
                currentMenuLink = $(event.target).closest('.sub-menu-container');
                currentMenuLink.addClass('focus');
            }
        }).focusout(function(event) {
            retainOrRelease--;
            if (retainOrRelease == 0) {
                currentMenuLink.removeClass('focus');
            }
        });
        
        // Add placeholder support for non-HTML5 browsers
        if (!Modernizr.placeholder) {
            $('input[placeholder], textarea[placeholder]').placeholder();
        }
        
        // Load TypeKit fonts
        if (window.Typekit) {
            try {
                window.Typekit.load();
            } catch(e) {
                
            }
        }
        
        // Initialize ColorBox script
        if ($.colorbox) {
            $('.colorbox, .lightbox').each(function() {
                var self = $(this);
                var options = self.data('options') || {};
                
                self.colorbox(options);
            });
        }
        
        // Initialize SoundCloud Player
        if (SC) {
            var currentTrackID;
            var currentTrackInput;
            var currentSoundObject;
            var cachedTrackData = [];
            var cachedSoundObjects = [];
            var playerInitialized = false;
            var playerState = 'paused';
            var playerIsMuted = false;
            var currentVolume = 100;
            var playerContainer = $('#soundcloud-player-container');
            var trackTitleElem = playerContainer.find('.track-title');
            var trackDurationElem = playerContainer.find('.track-duration');
            var playPauseButton = playerContainer.find('.play-pause');
            var previousButton = playerContainer.find('.previous');
            var nextButton = playerContainer.find('.next');
            var volumeButton = playerContainer.find('.volume');
            var showHidePlaylistButton = playerContainer.find('.track-list');
            var trackInputs = $('#soundcloud-track-id-list .track-id');
            currentTrackInput = trackInputs.first().addClass('current');
            currentTrackID = currentTrackInput.val();
            
            function formatTrackDuration(ms) {
                var seconds = Math.floor(ms / 1000);
                var minutes = Math.floor(seconds / 60);
                if (minutes) {
                    seconds -= minutes * 60;
                    minutes = (minutes < 10 ? '0' + minutes : minutes);
                } else {
                    minutes = '00';
                }
                seconds = (seconds < 10 ? '0' + seconds : seconds);
                return minutes + ":" + seconds;
            }
            
            function updateCurrentSoundObject(sound) {
                if (!cachedSoundObjects[currentTrackID]) {
                    cachedSoundObjects[currentTrackID] = sound;
                }
                if (cachedSoundObjects[currentTrackID]) {
                    currentSoundObject = cachedSoundObjects[currentTrackID];
                }
            }
            
            function updateCurrentTrackInfo() {
                if (playerInitialized) {
                    soundManager.stopAll();
                }
                currentTrackID = currentTrackInput.val();
                if (!cachedTrackData[currentTrackID]) {
                    SC.get("/tracks/" + currentTrackID, function(track, error){
                        if (error) {
                            console.log(error.message);
                            return false;
                        } else {
                            cachedTrackData[currentTrackID] = track;
                            updateTrack(track);
                        }
                    });
                } else {
                    var track = cachedTrackData[currentTrackID];
                    updateTrack(track);
                }
            }
            
            function updateTrack(track) {
                if (!track) {
                    console.log('updateTrack :: Invalid or missing track object');
                    return false;
                } else {
                    trackTitleElem.html(track.title);
                    trackDurationElem.html(formatTrackDuration(track.duration));
                    SC.stream('/tracks/' + currentTrackID, function(sound) {
                        updateCurrentSoundObject(sound);
                        if (playerState != 'paused') {
                            currentSoundObject.play();
                        }
                    });
                }
            }
            
            function playCurrentTrack() {
                playerState = 'playing';
                playPauseButton.removeClass('paused').addClass('playing');
                if (playerIsMuted) {
                    currentSoundObject.mute();
                }
                currentSoundObject.play();
            }
            
            function pauseCurrentTrack() {
                playerState = 'paused';
                playPauseButton.removeClass('playing').addClass('paused');
                currentSoundObject.pause();
            }
            
            updateCurrentTrackInfo();
            
            previousButton.click(function(event) {
                event.preventDefault();
                
                currentTrackInput = trackInputs.filter('.current');
                var newTrackInput = currentTrackInput.prev();
                
                if (!newTrackInput.length) {
                    newTrackInput = trackInputs.last();
                }
                
                if (newTrackInput.length) {
                    newTrackInput.addClass('current');
                    currentTrackInput.removeClass('current');
                    currentTrackInput = newTrackInput;
                    
                    updateCurrentTrackInfo();
                }
            });
            
            nextButton.click(function(event) {
                event.preventDefault();
                
                currentTrackInput = trackInputs.filter('.current');
                var newTrackInput = currentTrackInput.next();
                
                if (!newTrackInput.length) {
                    newTrackInput = trackInputs.first();
                }
                
                if (newTrackInput.length) {
                    newTrackInput.addClass('current');
                    currentTrackInput.removeClass('current');
                    currentTrackInput = newTrackInput;
                    
                    updateCurrentTrackInfo();
                }
            });
            
            playPauseButton.click(function(event) {
                event.preventDefault();
                if (!playerInitialized) {
                    playerInitialized = true;
                    playerState = 'playing';
                    SC.stream('/tracks/' + currentTrackID, function(sound) {
                        updateCurrentSoundObject(sound);
                        playCurrentTrack();
                    });
                } else {
                    switch (playerState) {
                        case 'paused' :
                            playCurrentTrack();
                        break;
                        case 'playing' :
                            pauseCurrentTrack();
                        break;
                        default :
                            if (window.console) console.log('Invalid player state; only "paused" and "playing" are accepted. Check your code for spelling errors.');
                        break;
                    }
                }
            });
            
            volumeButton.click(function(event) {
                event.preventDefault();
                if (playerIsMuted) {
                    playerIsMuted = false;
                    volumeButton.removeClass('muted');
                    currentSoundObject.unmute();
                } else {
                    playerIsMuted = true;
                    volumeButton.addClass('muted');
                    currentSoundObject.mute();
                }
            });
            
            showHidePlaylistButton.click(function(event) {
                event.preventDefault();
            });
        }
    });
})(jQuery);


// Custom Modernizr Tests
if (Modernizr) {
    Modernizr.addTest('placeholder', !!("placeholder" in document.createElement("input")));
    Modernizr.addTest('lastchild', function () {
        var hasLastChild,
            rules = ['#modernizr-last-child li{display:block;width:100px;height:100px;}','#modernizr-last-child li:last-child{width:200px;}'],
            head = document.getElementsByTagName('head')[0] || (function () {
                return document.documentElement.appendChild(document.createElement('head'));
            }()),
            root = document.body || (function () {
                return document.documentElement.appendChild(document.createElement('body'));
            }()),
            list = document.createElement('ul'),
            firstChild = document.createElement('li'),
            lastChild = document.createElement('li'),
            style = document.createElement('style');
            
        style.type = "text/css";
        if(style.styleSheet){ style.styleSheet.cssText = rules.join(''); } 
        else {style.appendChild(document.createTextNode(rules.join(''))); }
        head.appendChild(style);
        
        list.id = "modernizr-last-child";
        list.appendChild(firstChild);
        list.appendChild(lastChild);
        root.appendChild(list);
        hasLastChild = lastChild.offsetWidth > firstChild.offsetWidth;
        
        head.removeChild(style);
        root.removeChild(list);
        
        return hasLastChild;
    });
    Modernizr.addTest('firstchild', function () {
        var hasFirstChild,
            rules = ['#modernizr-first-child li{display:block;width:100px;height:100px;}','#modernizr-first-child li:first-child{width:200px;}'],
            head = document.getElementsByTagName('head')[0] || (function () {
                return document.documentElement.appendChild(document.createElement('head'));
            }()),
            root = document.body || (function () {
                return document.documentElement.appendChild(document.createElement('body'));
            }()),
            list = document.createElement('ul'),
            firstChild = document.createElement('li'),
            lastChild = document.createElement('li'),
            style = document.createElement('style');
            
        style.type = "text/css";
        if(style.styleSheet){ style.styleSheet.cssText = rules.join(''); } 
        else {style.appendChild(document.createTextNode(rules.join(''))); }
        head.appendChild(style);
        
        list.id = "modernizr-first-child";
        list.appendChild(firstChild);
        list.appendChild(lastChild);
        root.appendChild(list);
        hasFirstChild = firstChild.offsetWidth > lastChild.offsetWidth;
        
        head.removeChild(style);
        root.removeChild(list);
        
        return hasFirstChild;
    });
}