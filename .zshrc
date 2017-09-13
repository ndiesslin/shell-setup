# Path to your oh-my-zsh installation.
export ZSH=/Users/ndiesslin/.oh-my-zsh # Mac
#export ZSH=/home/nicholas/.oh-my-zsh # Linux

# Set name of the theme to load.
# Look in ~/.oh-my-zsh/themes/
# Optionally, if you set this to "random", it'll load a random theme each
# time that oh-my-zsh is loaded.
#ZSH_THEME="awesomepanda"
ZSH_THEME="agnoster"
DEFAULT_USER="ndiesslin" # Mac

# Which plugins would you like to load? (plugins can be found in ~/.oh-my-zsh/plugins/*)
# Custom plugins may be added to ~/.oh-my-zsh/custom/plugins/
# Example format: plugins=(rails git textmate ruby lighthouse)
# Add wisely, as too many plugins slow down shell startup.
export PATH=/opt/local/bin:$PATH
plugins=(git tmux)

# User configuration

#export PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin"
# export MANPATH="/usr/local/man:$MANPATH"

source $ZSH/oh-my-zsh.sh

[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm" # Load RVM into a shell session *as a function*

#MacPorts Installer addition on 2015-08-28_at_08:32:15: adding an appropriate PATH variable for use with MacPorts.
export PATH="/opt/local/bin:/opt/local/sbin:$PATH"
# Finished adapting your PATH environment variable for use with MacPorts.

alias mysqlstart='sudo /opt/local/share/mysql56/support-files/mysql.server start'
alias mysqlstop='sudo /opt/local/share/mysql56/support-files/mysql.server stop'
alias mysql='/opt/local/lib/mysql56/bin/mysql'
alias mysqladmin='/opt/local/lib/mysql56/bin/mysqladmin'

export PATH="$PATH:$HOME/.rvm/bin" # Add RVM to PATH for scripting

[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm" # Load RVM into a shell session *as a function*


# Apache control alias
alias apache2ctl='sudo /opt/local/apache2/bin/apachectl'

alias weather='/Users/ndiesslin/Applications/ansiweather/ansiweather' 

# Composer
alias composer="php /usr/local/bin/composer.phar"

# Asciiquarium
alias fish="/Applications/asciiquarium_1.1/asciiquarium"

PATH="/Users/ndiesslin/perl5/bin${PATH+:}${PATH}"; export PATH;
PERL5LIB="/Users/ndiesslin/perl5/lib/perl5${PERL5LIB+:}${PERL5LIB}"; export PERL5LIB;
PERL_LOCAL_LIB_ROOT="/Users/ndiesslin/perl5${PERL_LOCAL_LIB_ROOT+:}${PERL_LOCAL_LIB_ROOT}"; export PERL_LOCAL_LIB_ROOT;
PERL_MB_OPT="--install_base \"/Users/ndiesslin/perl5\""; export PERL_MB_OPT;
PERL_MM_OPT="INSTALL_BASE=/Users/ndiesslin/perl5"; export PERL_MM_OPT;

# Zplug stuff
source ~/.zplug/init.zsh

# Make sure to use double quotes
zplug "zsh-users/zsh-history-substring-search"

# Supports oh-my-zsh plugins and the like
zplug "plugins/git",   from:oh-my-zsh

# Group dependencies
# Load "emoji-cli" 
zplug "b4b4r07/emoji-cli"

# Note: To specify the order in which packages should be loaded, use the nice
#       tag described in the next section

# Set the priority when loading
# e.g., zsh-syntax-highlighting must be loaded
# after executing compinit command and sourcing other plugins
zplug "zsh-users/zsh-syntax-highlighting", nice:10

# Can manage local plugins
zplug "~/.zsh", from:local

# Install plugins if there are plugins that have not been installed
if ! zplug check --verbose; then
    printf "Install? [y/N]: "
    if read -q; then
        echo; zplug install
    fi
fi

# Then, source plugins and add commands to $PATH
zplug load --verbose

[ -f ~/.fzf.zsh ] && source ~/.fzf.zsh

export PATH=$PATH:/Applications/vv
