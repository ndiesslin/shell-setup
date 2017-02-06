set nocompatible              " be iMproved, required
filetype off                  " required

" Set leader key
let mapleader=","

set backspace=2
set expandtab " Spaces instead of tabs
set tabstop=2 " Soft tab to two spaces
set softtabstop=2 " Set stop for soft tabs
set shiftwidth=2
set list " set spaces as charachters
syntax on " enable syntax hilighting
set syntax=whitespace
set cursorline " highlight current line
set cursorcolumn
set wildmenu " Autocomplete vim commands with tab
set hlsearch " highlight matches
set number " Add number on side of editor

" Use the same symbols as TextMate for tabstops and EOLs
set listchars=tab:▸\ ,eol:¬,space:. 

" Key maps
nmap <leader>l :set list!<CR> " Shortcut to rapidly toggle `set list`
nmap <leader>k :set cursorcolumn!<CR> " Toggle cursor column
" turn off search highlight
nnoremap <leader><space> :nohlsearch<CR>
" jk is escape
inoremap jk <esc>
" move vertically by visual line
nnoremap j gj
nnoremap k gk

" Shift for colon sucks
nnoremap ; :
nnoremap : ;

" Enable spell check
set spell

" Auto indent
set autoindent

" Set file  backup directories
set backupdir=~/.vim/backup//
set directory=~/.vim/swap//
set undodir=~/.vim/undo//

" Set line wrapping correctly
set whichwrap+=<,>,h,l,[,]

" Enable mouse use in all modes
set mouse=a

" Correct pasting
set paste

" Correct copying
set clipboard=unnamed

" set the runtime path to include Vundle and initialize
set rtp+=~/.vim/bundle/Vundle.vim
call vundle#begin()

" let Vundle manage Vundle, required
Plugin 'VundleVim/Vundle.vim'

" Fuzzy finder
Plugin 'ctrlpvim/ctrlp.vim'
let g:ctrlp_map = '<c-t>'

" Git wrapper
Plugin 'tpope/vim-fugitive'

" File tree
Plugin 'scrooloose/nerdtree'
"map <C-n> :NERDTreeToggle<CR>
nnoremap <C-n> :NERDTreeToggle<CR>
" Close vim if nerdtree is only open
autocmd bufenter * if (winnr("$") == 1 && exists("b:NERDTree") && b:NERDTree.isTabTree()) | q | endif

Plugin 'Xuyuanp/nerdtree-git-plugin' " Git highlighting in nerdtree

" Syntax validator
Plugin 'scrooloose/syntastic'

" Cool visual bar
Plugin 'vim-airline/vim-airline'
Plugin 'vim-airline/vim-airline-themes'

" Text abreviations
Plugin 'mattn/emmet-vim'

" Multi selection
Plugin 'terryma/vim-multiple-cursors'

" Visually show git edits
Plugin 'mhinz/vim-signify'

" Default mapping for multi selection
let g:multi_cursor_next_key='<C-d>'
" let g:multi_cursor_prev_key='<C-p>'
" let g:multi_cursor_skip_key='<C-x>'
" let g:multi_cursor_quit_key='<Esc>'

" Auto Completion
Plugin 'ervandew/supertab'

" Comments
Plugin 'scrooloose/nerdcommenter' "To comment line or selected line type ,cc

" Folding
Plugin 'tmhedberg/SimpylFold'

Plugin 'lumiliet/vim-twig' " twig highlighting

Plugin 'ap/vim-css-color' " CSS color highlighting

Plugin 'Raimondi/delimitMate' " Auto completion for quotes and brackets

" Easy vim Navigation 
Plugin 'christoomey/vim-tmux-navigator'

" HTML tag matching
Plugin 'gregsexton/MatchTag'

Plugin 'L9'

" Colors
" Bundle 'altercation/vim-colors-solarized'
Bundle 'morhetz/gruvbox'

" All of your Plugins must be added before the following line
call vundle#end()            " required
filetype plugin indent on    " required
" To ignore plugin indent changes, instead use:
" filetype plugin on

" Brief help
" :PluginList       - lists configured plugins
" :PluginInstall    - installs plugins; append `!` to update or just
" :PluginUpdate
" :PluginSearch foo - searches for foo; append `!` to refresh local cache
" :PluginClean      - confirms removal of unused plugins; append `!` to auto-approve removal

" see :h vundle for more details or wiki for FAQ
" Put your non-Plugin stuff after this line

colorscheme gruvbox
set background=dark
set laststatus=2
