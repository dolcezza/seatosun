;(function($) {
    // Fallback for browsers that don't support console.log
    if (!window.console) {
        window.console = {
            log : function() {
                
            },
            debug : function() {
                
            }
        };
    }
    
    // Get the size of a native JavaScript Object
    if (!$.objectSize) {
        $.objectSize = function(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };
    }
    
    $(function() {
        // Add placeholder support for non-HTML5 browsers
        if (!Modernizr.placeholder) {
            $('input[placeholder], textarea[placeholder]').placeholder();
        }
        
        // Videos page
        var videoEmbed = $('#video-player-container iframe');
        if (videoEmbed.length) {
            $('.seatosun_video').find('.wp-post-image, .video-title').click(function(event) {
                event.preventDefault();
                var self = $(this);
                var embedURL = self.closest('[data-embed-url]').attr('data-embed-url');
                if (embedURL) {
                    var currentEmbedURL = videoEmbed.attr('src');
                    if (embedURL != currentEmbedURL) {
                        videoEmbed.attr('src', embedURL);
                    }
                }
            });
        }
        
        // Initialize SoundCloud Player
        if (SC) {
            var soundCloudPlayer = {
                /* ----------------------------------------------------------
                   Variables
                   ---------------------------------------------------------- */
                
                // Internal variables
                playerInitialized : false,
                currentTrackID : null,
                currentTrackIDList : null,
                currentSoundObject : null,
                cachedTrackData : [],
                cachedSoundObjects : [],
                firstTrackNumber : 0,
                lastTrackNumber : 0,
                currentTrackNumber : 0,
                playerState : 'paused',
                playerIsMuted : false,
                currentVolume : 100,
                
                // Player elements
                playerContainer : null,
                trackTitleElem : null,
                trackDurationElem : null,
                playPauseButton : null,
                previousButton : null,
                nextButton : null,
                volumeButton : null,
                showHidePlaylistButton : null,
                
                /* ----------------------------------------------------------
                   Functions
                   ---------------------------------------------------------- */
                
                // Initialize the player
                init : function(playerContainerElement) {
                    // Set player container element
                    if (playerContainerElement) {
                        this.playerContainer = playerContainerElement;
                    } else {
                        this.playerContainer = $('#soundcloud-player-container');
                    }
                    
                    // Unbind any existing click events
                    this.playerContainer.find('*').off('click.scPlayer');
                    
                    // Set config
                    if (this.playerContainer.data('current-track-id-list')) {
                        this.currentTrackIDList = this.playerContainer.data('current-track-id-list');
                    }
                    if (this.playerContainer.data('current-track-id')) {
                        this.currentTrackID = this.playerContainer.data('current-track-id');
                    }
                    if (this.playerContainer.data('current-track-number')) {
                        this.currentTrackNumber = this.playerContainer.data('current-track-number');
                    }
                    
                    // Set player elements
                    this.trackTitleElem = this.playerContainer.find('.track-title');
                    this.trackDurationElem = this.playerContainer.find('.track-duration');
                    this.playPauseButton = this.playerContainer.find('.play-pause');
                    this.previousButton = this.playerContainer.find('.previous');
                    this.nextButton = this.playerContainer.find('.next');
                    this.volumeButton = this.playerContainer.find('.volume');
                    this.showHidePlaylistButton = this.playerContainer.find('.track-list');
                    
                    // Show player if it's hidden
                    if (!this.playerContainer.is(':visible')) {
                        this.playerContainer.fadeIn();
                    }
                    
                    // Set initial track
                    this.updateCurrentTrackID();
                    this.updateCurrentTrackInfo();
                    
                    // Add player event handlers
                    this.previousButton.on('click.scPlayer', function(event) {
                        event.preventDefault();
                        
                        soundCloudPlayer.playPreviousTrack();
                    });

                    this.nextButton.on('click.scPlayer', function(event) {
                        event.preventDefault();
                        
                        soundCloudPlayer.playNextTrack();
                    });

                    this.playPauseButton.on('click.scPlayer', function(event) {
                        event.preventDefault();
                        
                        if (!soundCloudPlayer.playerInitialized) {
				if(soundCloudPlayer.playerState == 'playing')
				{
					soundCloudPlayer.pauseCurrentTrack();
				}
				else
				{
                            soundCloudPlayer.playerInitialized = true;
                            soundCloudPlayer.playerState = 'playing';
                            SC.stream('/tracks/' + soundCloudPlayer.currentTrackID, function(sound) {
                                soundCloudPlayer.updateCurrentSoundObject(sound);

                                soundCloudPlayer.playCurrentTrack();
                            });
				}
                        } else {
                            switch (soundCloudPlayer.playerState) {
                                case 'paused' :
                                    soundCloudPlayer.playCurrentTrack();
                                break;
                                case 'playing' :
                                    soundCloudPlayer.pauseCurrentTrack();
                                break;
                                default :
                                    console.log('Invalid player state; only "paused" and "playing" are accepted. Check your code for spelling errors.');
                                break;
                            }
                        }
                    });

                    this.volumeButton.on('click.scPlayer', function(event) {
                        event.preventDefault();
                        
                        if (soundCloudPlayer.playerIsMuted) {
                            soundCloudPlayer.playerIsMuted = false;
                            soundCloudPlayer.volumeButton.removeClass('muted');
                            soundCloudPlayer.currentSoundObject.unmute();
                        } else {
                            soundCloudPlayer.playerIsMuted = true;
                            soundCloudPlayer.volumeButton.addClass('muted');
                            soundCloudPlayer.currentSoundObject.mute();
                        }
                    });

                    this.showHidePlaylistButton.on('click.scPlayer', function(event) {
                        event.preventDefault();
                        
                        soundCloudPlayer.showHidePlaylistButton.toggleClass('visible');
                    });
                },
                
                // Set internal variables
                updateCurrentSoundObject : function(sound) {
                    if (!soundCloudPlayer.cachedSoundObjects[soundCloudPlayer.currentTrackID]) {
                        soundCloudPlayer.cachedSoundObjects[soundCloudPlayer.currentTrackID] = sound;
                    }
                    if (soundCloudPlayer.cachedSoundObjects[soundCloudPlayer.currentTrackID]) {
                        soundCloudPlayer.currentSoundObject = soundCloudPlayer.cachedSoundObjects[soundCloudPlayer.currentTrackID];
                    }
                },
                
                updateCurrentTrackIDList : function(idList) {
                    if (!idList) {
                        idList = soundCloudPlayer.playerContainer.data('current-track-id-list');
                    }
                    if (!idList) {
                        idList = soundCloudPlayer.playerContainer.attr('data-track-id-list');
                    }
                    if (!idList) {
                        idList = soundCloudPlayer.playerContainer.find('[data-track-id-list]').attr('data-track-id-list');
                    }
                    if (idList) {
                        if (typeof idList != 'object') {
                            try {
                                idList = $.parseJSON(idList);
                                soundCloudPlayer.currentTrackIDList = idList;
                                soundCloudPlayer.firstTrackNumber = 0;
                                soundCloudPlayer.lastTrackNumber = $.objectSize(soundCloudPlayer.currentTrackIDList) - 1;
                                soundCloudPlayer.currentTrackNumber = soundCloudPlayer.firstTrackNumber;
                                
                                soundCloudPlayer.playerContainer.data('current-track-id-list', idList).data('current-track-number', soundCloudPlayer.currentTrackNumber);
                            } catch (error) {
                                console.log(error);
                            }
                        }
                    }
                },
                
                updateCurrentTrackID : function(id) {
                    if (!soundCloudPlayer.currentTrackIDList) {
                        soundCloudPlayer.updateCurrentTrackIDList();
                    }
                    if (!id) {
                        id = soundCloudPlayer.playerContainer.data('current-track-id');
                    }
                    if (!id) {
                        id = soundCloudPlayer.currentTrackIDList[soundCloudPlayer.currentTrackNumber];
                    }
                    if (id) {
                        soundCloudPlayer.currentTrackID = id;
                        soundCloudPlayer.playerContainer.data('current-track-id', id);
                    }
                },
                
                updateCurrentTrackNumber : function(trackNumber) {
                    if (trackNumber != null) {
                        soundCloudPlayer.currentTrackNumber = trackNumber;
                        soundCloudPlayer.playerContainer.data('current-track-number', trackNumber);
                    }
                },
                
                updateCurrentTrackInfo : function() {
                    if (soundCloudPlayer.playerInitialized) {
                        soundManager.stopAll();
                    }
                    
                    if (!soundCloudPlayer.currentTrackIDList) {
                        soundCloudPlayer.updateCurrentTrackIDList();
                    }
                    
                    if (!soundCloudPlayer.currentTrackID) {
                        soundCloudPlayer.updateCurrentTrackID();
                    }
                    
                    soundCloudPlayer.currentTrackID = soundCloudPlayer.currentTrackIDList[soundCloudPlayer.currentTrackNumber];
                    
                    if (!soundCloudPlayer.cachedTrackData[soundCloudPlayer.currentTrackID]) {
                        SC.get("/tracks/" + soundCloudPlayer.currentTrackID, function(track, error){
                            if (error) {
                                console.log(error.message);
                                return false;
                            } else {
                                soundCloudPlayer.cachedTrackData[soundCloudPlayer.currentTrackID] = track;
                                soundCloudPlayer.updateTrack(track);
                            }
                        });
                    } else {
                        var track = soundCloudPlayer.cachedTrackData[soundCloudPlayer.currentTrackID];
                        soundCloudPlayer.updateTrack(track);
                    }
                    
                    console.log(soundCloudPlayer.playerContainer.data('current-track-id'));
                },
                
                updateTrack : function(track) {
                    if (!track) {
                        console.log('updateTrack :: Invalid or missing track object');
                        return false;
                    } else {
                        soundCloudPlayer.trackTitleElem.html(track.title);
			raiseMainSail();
                        soundCloudPlayer.trackDurationElem.html(soundCloudPlayer.formatTrackDuration(track.duration));
			if (soundCloudPlayer.currentSoundObject != null) {
	 soundCloudPlayer.currentSoundObject.stop();
			}
                        SC.stream('/tracks/' + soundCloudPlayer.currentTrackID, function(sound) {
                            soundCloudPlayer.updateCurrentSoundObject(sound);
                            if ((soundCloudPlayer.playerState != 'paused')||gunning_it) {
				if(soundCloudPlayer.playerState =='paused')
				{
					soundCloudPlayer.playerState = "playing"; 
			soundCloudPlayer.playPauseButton.removeClass('paused').addClass('playing');
				}
				gunning_it=false; soundCloudPlayer.currentSoundObject.play();
				
                            }
                        });
                    }
                },
                
                playCurrentTrack : function() {
                    soundCloudPlayer.playerState = 'playing';
                    soundCloudPlayer.playPauseButton.removeClass('paused').addClass('playing');
                    if (soundCloudPlayer.playerIsMuted) {
                        soundCloudPlayer.currentSoundObject.mute();
                    }
                    soundCloudPlayer.currentSoundObject.play();
                },
                
                pauseCurrentTrack : function() {
                    soundCloudPlayer.playerState = 'paused';
                    soundCloudPlayer.playPauseButton.removeClass('playing').addClass('paused');
			if (soundCloudPlayer.currentSoundObject!=null)
			{
                    soundCloudPlayer.currentSoundObject.pause();
			}
					gunning_it=false;
                },
                
                playNextOrPreviousTrack : function(nextOrPrev) {
                    var newTrackNumber;
                    var newTrackID;
                    
                    switch (nextOrPrev) {
                        case 'prev' :
                        case 'previous' :
                            newTrackNumber = soundCloudPlayer.currentTrackNumber - 1;
                            newTrackID = soundCloudPlayer.currentTrackIDList[newTrackNumber];
                            if (!newTrackID) {
                                newTrackNumber = soundCloudPlayer.lastTrackNumber;
                                newTrackID = soundCloudPlayer.currentTrackIDList[newTrackNumber];
                            }
                        break;
                        
                        case 'next' :
                        default :
                            newTrackNumber = soundCloudPlayer.currentTrackNumber + 1;
                            newTrackID = soundCloudPlayer.currentTrackIDList[newTrackNumber];
                            if (!newTrackID) {
                                newTrackNumber = soundCloudPlayer.firstTrackNumber;
                                newTrackID = soundCloudPlayer.currentTrackIDList[newTrackNumber];
                            }
                        break;
                    }
                    
                    if (newTrackID) {
                        soundCloudPlayer.updateCurrentTrackNumber(newTrackNumber);
                        soundCloudPlayer.updateCurrentTrackID(newTrackID);
                        soundCloudPlayer.updateCurrentTrackInfo();
                    }
                },
                
                playNextTrack : function() {
                    soundCloudPlayer.playNextOrPreviousTrack('next');
                },
                
                playPreviousTrack : function() {
                    this.playNextOrPreviousTrack('previous');
                },
                
                formatTrackDuration : function(ms) {
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
                },
            };
            
            // Radio widget
            var widgetPlayerContainer = $('#soundcloud-player-container');
            soundCloudPlayer.init(widgetPlayerContainer);
            
            // Releases archive page
            var releasesPlayerContainer = $('#releases-soundcloud-player-container');
            if (releasesPlayerContainer.length) {
                // Enable player-switching functionality
                $('.seatosun_release').on('click.scPlayerInit', function(event) {
                    event.preventDefault();
                    var self = $(this);
                    
                    var trackIDList = self.attr('data-track-id-list');
                    if (!trackIDList) {
                        trackIDList = [self.attr('data-release-id')];
                    }
                    
                   soundCloudPlayer.updateCurrentTrackIDList(trackIDList);
                   soundCloudPlayer.init(releasesPlayerContainer); 
                });
                
                $('#soundcloud-player-container .player-controls .play-pause').on('click.scPlayerInit', function(event) {
                    event.preventDefault();
                    var self = $(this);
                    
                    soundCloudPlayer.init(widgetPlayerContainer);
                });
            }
        }
    });
})(jQuery);


// Custom Modernizr Tests
if (Modernizr) {
    Modernizr.addTest('placeholder', !!("placeholder" in document.createElement("input")));
}

//Special thanks to Joel Potter for this jQuery marquee code
//FLJ, 5/25/2013
var marquee = null;
function raiseMainSail() {

    marquee = $("#marquee"); 
    marquee.css({"overflow": "hidden", "width": "100%","line-height":"0.7"});

    // wrap "My Text" with a span (old versions of IE don't like divs inline-block)
    marquee.wrapInner("<span>");
    marquee.find("span").css({ "width": "50%", "display": "inline-block", "text-align":"center" }); 
    marquee.append(marquee.find("span").clone()); // now there are two spans with "My Text"

    // create an inner div twice as wide as the view port for animating the scroll
    marquee.wrapInner("<div style='line-height:0.7;'>");
    marquee.find("div").css("width", "600%");

    // create a function which animates the div
    // $.animate takes a callback for when the animation completes
    var reset = function() {
        $(this).css("margin-left", "0%");
        $(this).animate({ "margin-left": "-300%" }, 12000, 'linear', reset);
    };

    // kick it off
    reset.call(marquee.find("div"));

}
