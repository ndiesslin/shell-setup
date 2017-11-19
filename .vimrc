set nocompatible              " be iMproved, required
filetype plugin indent on     " required


" Set to auto read when a file is changed from the outside
set autoread

" Set leader key
let mapleader=","

syntax on " enable syntax hilighting

"
" Key Mapping 
"
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

nnoremap ; :
nnoremap : ;

" Format pasting indentation correctly
:nnoremap p ]p
" Control+p for default p
:nnoremap <c-p> p

" Tab navigation like Chrome.
"nnoremap <C-S-tab> :tabprevious<CR>
"nnoremap <C-tab>   :tabnext<CR>
"nnoremap <C><t>     :tabnew<CR>
"inoremap <C-S-tab> <Esc>:tabprevious<CR>i
"inoremap <C-tab>   <Esc>:tabnext<CR>i
"inoremap <C-t>     <Esc>:tabnew<CR>

" Enable spell check
set spell

" Set file  backup directories
set backupdir=~/.vim/backup//
set directory=~/.vim/swap//
set undodir=~/.vim/undo//

"
" Formatting
"
" Use the same symbols as TextMate for tabstops and EOLs
set listchars=tab:▸\ ,eol:¬,space:· 

" Set line wrapping correctly
set whichwrap+=<,>,h,l,[,]

" Set column limit number
"highlight ColorColumn ctermbg=gray
"set colorcolumn=80

set wrap " Wrap long lines

" Enable mouse use in all modes
set mouse=a

" Correct pasting
set paste

" Correct copying
set clipboard=unnamed

" set the runtime path to include Vundle and initialize
set backspace=2
set tabstop=2 " Soft tab to two spaces
set softtabstop=2 " Set stop for soft tabs
set shiftwidth=2
set smarttab " Tab will match indentation line
set list " set spaces as charachters
set syntax=whitespace
set cursorline " highlight current line
set cursorcolumn
set wildmenu " Autocomplete vim commands with tab
set hlsearch " highlight matches
set number " Add number on side of editor

set expandtab " Spaces instead of tabs
set ai " Auto indent
set si " Smart indent

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

"Plugin 'gregsexton/MatchTag' " HTML tag matching

"Plugin 'pangloss/vim-javascript' " JavaScript indentation support

"Plugin 'valloric/youcompleteme' " Autocomplete

" Indentation
"Plugin 'nathanaelkane/vim-indent-guides'

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
