@extends('layouts.app')

@section('title', 'EduSync - Academic Intelligence System')

@section('content')

    <div x-data="eduSyncApp()" class="max-w-7xl mx-auto space-y-8 md:space-y-16 pb-20 md:pb-40 text-amber-900 w-full animate-reveal">

        <!-- HEADER -->
        <section class="flex flex-col md:flex-row justify-between items-start md:items-end border-b pb-6 md:pb-10 gap-4">
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold italic tracking-tight text-amber-900">
                Edu<span class="text-amber-700">.Sync</span>
                <span class="text-amber-700">by Nexo</span>
            </h2>

            <nav
                class="flex bg-amber-100 p-1.5 rounded-xl md:rounded-[1.5rem] border border-black/5 w-full md:w-fit shadow-inner overflow-x-auto no-scrollbar">
                <template x-for="m in ['Hub','Library','Chem Lab','AI Lab']" :key="m">
                    <button @click="setMode(m)" :class="mode === m ? 'bg-amber-800 text-white' : 'bg-amber-50'"
                        class="px-4 sm:px-5 md:px-6 py-2.5 md:py-3 rounded-full text-[10px] md:text-xs font-bold uppercase whitespace-nowrap" x-text="m"></button>
                </template>
            </nav>
        </section>

        <!-- HUB -->
        <div x-show="mode === 'Hub'" x-transition.opacity.duration.300ms>
            <div x-data="hubAI()" class="max-w-7xl mx-auto space-y-8 md:space-y-16">

                <!-- Hero Header -->
                <div
                    class="relative overflow-hidden bg-gradient-to-br from-black via-stone-950 to-black text-white p-6 sm:p-10 md:p-12 lg:p-16 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-lg sm:shadow-xl md:shadow-2xl">
                    <div
                        class="absolute top-0 right-0 w-48 h-48 sm:w-64 sm:h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 bg-amber-600/30 rounded-full blur-3xl animate-[pulse_8s_ease-in-out_infinite]">
                    </div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 sm:w-64 sm:h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 bg-amber-700/20 rounded-full blur-3xl animate-[pulse_10s_ease-in-out_infinite]"
                        style="animation-delay: 2s;"></div>

                    <div class="relative z-10 text-center space-y-4 md:space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 bg-amber-600/20 rounded-full border border-amber-600/30 backdrop-blur-sm mb-3 md:mb-4">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            <span class="text-xs font-bold text-amber-300 uppercase tracking-wider">AI-Powered
                                Learning</span>
                        </div>

                        <h2
                            class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-serif italic font-black bg-gradient-to-r from-white via-amber-200 to-white bg-clip-text text-transparent">
                            Edu<span class="text-amber-500">.AI</span> Hub
                        </h2>

                        <p class="text-base sm:text-lg md:text-xl text-white/70 max-w-2xl mx-auto font-medium">
                            Generate intelligent quizzes and flashcards powered by advanced AI technology
                        </p>
                    </div>
                </div>

                <!-- Input Section -->
                <div class="relative">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-amber-700 to-amber-600 rounded-3xl blur opacity-20">
                    </div>
                    <div class="relative bg-white rounded-xl sm:rounded-2xl md:rounded-3xl shadow-lg sm:shadow-xl p-6 sm:p-8 md:p-10 space-y-6 sm:space-y-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-amber-700 to-amber-800 flex items-center justify-center">
                                <i class="fa-solid fa-sliders text-white"></i>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-bold italic text-amber-900 tracking-tight">Configure Your Learning</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label
                                    class="text-xs font-black text-amber-700 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-graduation-cap text-amber-700"></i>
                                    Grade Level
                                </label>
                                <input x-model="grade" type="number" min="3" max="10"
                                    placeholder="Enter grade (3-10)"
                                    class="w-full px-6 py-4 rounded-2xl border-2 border-amber-200 focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10 transition-all outline-none font-semibold text-lg">
                            </div>

                            <div class="space-y-3">
                                <label
                                    class="text-xs font-black text-amber-700 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-book-open text-amber-700"></i>
                                    Topic
                                </label>
                                <input x-model="topic" placeholder="e.g. Photosynthesis, Fractions..."
                                    class="w-full px-6 py-4 rounded-2xl border-2 border-amber-200 focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10 transition-all outline-none font-semibold text-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <button @click="loadQuiz()" :disabled="loadingQuiz || !grade || !topic"
                                class="group relative px-8 py-5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 overflow-hidden">
                                <span class="relative flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-clipboard-question"></i>
                                    <span x-show="!loadingQuiz">Generate Quiz</span>
                                    <span x-show="loadingQuiz">Generating...</span>
                                </span>
                            </button>

                            <button @click="loadFlash()" :disabled="loadingFlash || !grade || !topic"
                                class="group relative px-8 py-5 bg-gradient-to-r from-amber-700 to-amber-600 text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <span class="relative flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span x-show="!loadingFlash">Generate Flashcards</span>
                                    <span x-show="loadingFlash">Generating...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- INTERACTIVE QUIZ -->
                <template x-if="quiz.length">
                    <section class="space-y-8 opacity-0 animate-[fadeIn_0.6s_ease-out_forwards]">
                        <div
                            class="flex items-center justify-between p-8 bg-gradient-to-r from-amber-800 to-amber-700 text-white rounded-3xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                    <i class="fa-solid fa-brain text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-bold italic text-white tracking-tight">Interactive Quiz</h3>
                                    <p class="text-white/70">Grade <span x-text="grade"></span> - <span
                                            x-text="topic"></span></p>
                                </div>
                            </div>
                            <div x-show="!quizCompleted" class="text-right">
                                <p class="text-sm text-white/60 font-bold uppercase tracking-wider">Question</p>
                                <p class="text-3xl font-black font-serif"><span x-text="currentQuestion + 1"></span>/<span
                                        x-text="quiz.length"></span></p>
                            </div>
                        </div>

                        <template x-if="!quizCompleted">
                            <div class="relative">
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-orange-600 to-orange-400 rounded-3xl blur opacity-20">
                                </div>
                                <div class="relative bg-white rounded-3xl shadow-2xl p-10 space-y-8">
                                    <template x-for="(q, i) in quiz" :key="i">
                                        <div x-show="i === currentQuestion" x-transition class="space-y-6 md:space-y-8">
                                            <div class="flex items-start gap-3 md:gap-4">
                                                <div
                                                    class="w-10 h-10 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-base md:text-lg flex-shrink-0">
                                                    <span x-text="i + 1"></span>
                                                </div>
                                                <p class="text-lg sm:text-xl md:text-2xl font-bold text-stone-900 leading-relaxed"
                                                    x-text="q.question"></p>
                                            </div>

                                            <div class="grid gap-3 md:gap-4">
                                                <template x-for="(option, idx) in q.options" :key="idx">
                                                    <button @click="selectAnswer(i, idx, option)"
                                                        :disabled="userAnswers[i] !== undefined"
                                                        :class="{
                                                            'border-green-500 bg-green-50': userAnswers[i] !==
                                                                undefined && idx === getCorrectAnswerIndex(q),
                                                            'border-red-500 bg-red-50': userAnswers[i] !== undefined &&
                                                                userAnswers[i].selectedIndex === idx && idx !== getCorrectAnswerIndex(q),
                                                            'border-amber-200 hover:border-amber-300 hover:bg-amber-50/50': userAnswers[
                                                                i] === undefined
                                                        }"
                                                        class="group text-left px-4 sm:px-5 md:px-6 py-4 md:py-5 rounded-xl md:rounded-2xl border-2 transition-all duration-300 disabled:cursor-not-allowed">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-3 md:gap-4">
                                                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl border-2 flex items-center justify-center font-bold text-xs md:text-sm transition-all"
                                                                    :class="{
                                                                        'border-green-500 bg-green-500 text-white': userAnswers[
                                                                            i] !== undefined && idx === getCorrectAnswerIndex(q),
                                                                        'border-red-500 bg-red-500 text-white': userAnswers[
                                                                                i] !== undefined && userAnswers[i]
                                                                            .selectedIndex === idx && idx !== getCorrectAnswerIndex(q),
                                                                        'border-amber-300 text-amber-700 group-hover:border-amber-500': userAnswers[
                                                                            i] === undefined
                                                                    }">
                                                                    <span x-text="String.fromCharCode(65 + idx)"></span>
                                                                </div>
                                                                <span class="text-base md:text-lg font-semibold text-amber-800"
                                                                    x-text="option"></span>
                                                            </div>
                                                            <div x-show="userAnswers[i] !== undefined">
                                                                <i x-show="idx === getCorrectAnswerIndex(q)"
                                                                    class="fa-solid fa-circle-check text-2xl text-green-500"></i>
                                                                <i x-show="userAnswers[i].selectedIndex === idx && idx !== getCorrectAnswerIndex(q)"
                                                                    class="fa-solid fa-circle-xmark text-2xl text-red-500"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>

                                            <div class="flex items-center justify-between pt-4 md:pt-6 border-t border-amber-100">
                                                <button @click="previousQuestion()" x-show="currentQuestion > 0"
                                                    class="px-4 md:px-6 py-2.5 md:py-3 rounded-lg md:rounded-xl border-2 border-amber-200 text-amber-700 font-bold text-sm md:text-base hover:border-amber-300 hover:bg-amber-50 transition-all">
                                                    <i class="fa-solid fa-arrow-left mr-1 md:mr-2"></i> <span class="hidden sm:inline">Previous</span><span class="sm:hidden">Prev</span>
                                                </button>
                                                <div></div>
                                                <button @click="nextQuestion()" x-show="currentQuestion < quiz.length - 1"
                                                    :disabled="userAnswers[currentQuestion] === undefined"
                                                    class="px-4 md:px-6 py-2.5 md:py-3 rounded-lg md:rounded-xl bg-gradient-to-r from-amber-700 to-amber-600 text-white font-bold text-sm md:text-base hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Next <i class="fa-solid fa-arrow-right ml-1 md:ml-2"></i>
                                                </button>
                                                <button @click="completeQuiz()"
                                                    x-show="currentQuestion === quiz.length - 1"
                                                    :disabled="userAnswers[currentQuestion] === undefined"
                                                    class="px-4 sm:px-6 md:px-8 py-2.5 md:py-3 rounded-lg md:rounded-xl bg-gradient-to-r from-green-600 to-green-500 text-white font-bold text-sm md:text-base hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <span class="hidden sm:inline">Complete Quiz</span><span class="sm:hidden">Complete</span> <i class="fa-solid fa-check ml-1 md:ml-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Quiz Results -->
                        <template x-if="quizCompleted">
                            <div class="relative">
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-green-600 to-green-400 rounded-3xl blur opacity-30">
                                </div>
                                <div class="relative bg-white rounded-3xl shadow-2xl p-12 text-center space-y-8">
                                    <div
                                        class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white shadow-2xl">
                                        <i class="fa-solid fa-trophy text-6xl"></i>
                                    </div>

                                    <div class="space-y-4">
                                        <h3 class="text-6xl font-bold italic text-amber-900 tracking-tight">Quiz Complete!
                                        </h3>
                                        <p class="text-2xl text-amber-700 font-medium">Here's how you performed</p>
                                    </div>

                                    <div
                                        class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 md:gap-8 px-6 sm:px-8 md:px-12 py-6 sm:py-7 md:py-8 bg-gradient-to-br from-amber-50 to-white rounded-2xl md:rounded-3xl border-2 border-amber-100 shadow-lg">
                                        <div class="text-center">
                                            <p class="text-xs sm:text-sm text-amber-700 font-black uppercase tracking-wider mb-2">Your
                                                Score</p>
                                            <p
                                                class="text-4xl sm:text-5xl md:text-7xl font-black font-serif bg-gradient-to-br from-green-600 to-green-500 bg-clip-text text-transparent">
                                                <span x-text="calculateScore()"></span>/<span x-text="quiz.length"></span>
                                            </p>
                                        </div>
                                        <div class="h-px w-20 sm:h-20 sm:w-px bg-amber-200"></div>
                                        <div class="text-center">
                                            <p class="text-xs sm:text-sm text-amber-700 font-black uppercase tracking-wider mb-2">
                                                Percentage</p>
                                            <p
                                                class="text-4xl sm:text-5xl md:text-7xl font-black font-serif bg-gradient-to-br from-amber-700 to-amber-600 bg-clip-text text-transparent">
                                                <span x-text="Math.round((calculateScore() / quiz.length) * 100)"></span>%
                                            </p>
                                        </div>
                                    </div>

                                    <div class="p-6 rounded-2xl"
                                        :class="calculateScore() / quiz.length >= 0.8 ? 'bg-green-50 border border-green-200' :
                                            'bg-amber-50 border border-amber-200'">
                                        <p class="text-xl font-bold"
                                            :class="calculateScore() / quiz.length >= 0.8 ? 'text-green-700' : 'text-amber-700'">>
                                            <span x-show="calculateScore() / quiz.length >= 0.8">🎉 Excellent work! You've
                                                mastered this topic!</span>
                                            <span x-show="calculateScore() / quiz.length < 0.8">📚 Good effort! Review the
                                                lesson to improve your understanding.</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center pt-4">
                                        <button @click="resetQuiz()"
                                            class="px-6 sm:px-7 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl bg-gradient-to-r from-amber-700 to-amber-600 text-white font-bold text-base md:text-lg hover:shadow-lg transition-all hover:scale-105">
                                            <i class="fa-solid fa-rotate-right mr-2"></i> Retake Quiz
                                        </button>
                                        <button @click="quiz = []; quizCompleted = false;"
                                            class="px-6 sm:px-7 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl border-2 border-amber-200 text-amber-700 font-bold text-base md:text-lg hover:border-amber-300 hover:bg-amber-50 transition-all">
                                            <i class="fa-solid fa-arrow-left mr-2"></i> New Quiz
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </section>
                </template>

                <!-- FLASHCARDS -->
                <template x-if="cards.length">
                    <section class="space-y-8 opacity-0 animate-[fadeIn_0.6s_ease-out_0.3s_forwards]">
                        <div
                            class="flex items-center justify-between p-8 bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-3xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                    <i class="fa-solid fa-layer-group text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-bold italic text-white tracking-tight">Flashcards</h3>
                                    <p class="text-white/80"><span x-text="cards.length"></span> cards generated</p>
                                </div>
                            </div>
                            <button @click="shuffleCards()"
                                class="px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl font-bold transition-all">
                                <i class="fa-solid fa-shuffle mr-2"></i> Shuffle
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                            <template x-for="(card, index) in cards" :key="index">
                                <div @click="flipCard(index)" class="group cursor-pointer"
                                    style="perspective: 1000px; min-height: 240px;">
                                    <div class="relative w-full h-full transition-transform duration-700"
                                        :class="flippedCards[index] ? 'rotate-y-180' : ''"
                                        style="transform-style: preserve-3d;">

                                        <!-- Front -->
                                        <div class="absolute inset-0" style="backface-visibility: hidden;">
                                            <div class="relative h-full">
                                                <div
                                                    class="absolute -inset-0.5 bg-gradient-to-r from-orange-600 to-orange-400 rounded-3xl blur opacity-30 group-hover:opacity-50 transition-opacity">
                                                </div>
                                                <div
                                                    class="relative h-full bg-white rounded-2xl md:rounded-3xl shadow-xl p-5 sm:p-6 md:p-8 flex flex-col justify-between hover:shadow-2xl transition-all">
                                                    <div class="space-y-3 md:space-y-4">
                                                        <div class="flex items-center justify-between">
                                                            <div
                                                                class="px-2.5 md:px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-[10px] md:text-xs font-bold">
                                                                Question</div>
                                                            <span class="text-xs md:text-sm font-bold text-stone-400">#<span
                                                                    x-text="index + 1"></span></span>
                                                        </div>
                                                        <p class="text-base sm:text-lg md:text-xl font-bold text-stone-900 leading-relaxed"
                                                            x-text="card.front"></p>
                                                    </div>
                                                    <div
                                                        class="flex items-center justify-center gap-2 text-orange-600 text-sm font-bold">
                                                        <i class="fa-solid fa-hand-pointer animate-pulse"></i>
                                                        Click to reveal
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Back -->
                                        <div class="absolute inset-0"
                                            style="backface-visibility: hidden; transform: rotateY(180deg);">
                                            <div class="relative h-full">
                                                <div
                                                    class="absolute -inset-0.5 bg-gradient-to-r from-green-600 to-green-400 rounded-3xl blur opacity-30">
                                                </div>
                                                <div
                                                    class="relative h-full bg-gradient-to-br from-green-600 to-green-500 rounded-2xl md:rounded-3xl shadow-xl p-5 sm:p-6 md:p-8 flex flex-col justify-between text-white">
                                                    <div class="space-y-3 md:space-y-4">
                                                        <div class="flex items-center justify-between">
                                                            <div
                                                                class="px-2.5 md:px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-[10px] md:text-xs font-bold">
                                                                Answer</div>
                                                            <i class="fa-solid fa-check-circle text-xl md:text-2xl"></i>
                                                        </div>
                                                        <p class="text-base md:text-lg font-semibold leading-relaxed"
                                                            x-text="card.back"></p>
                                                    </div>
                                                    <div
                                                        class="flex items-center justify-center gap-2 text-white/80 text-sm font-bold">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                        Click to flip
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-center gap-4">
                            <button @click="cards = []; flippedCards = {}"
                                class="px-6 py-3 rounded-xl border-2 border-stone-200 text-stone-700 font-bold hover:border-stone-300 hover:bg-stone-50 transition-all">
                                <i class="fa-solid fa-xmark mr-2"></i> Clear Cards
                            </button>
                        </div>
                    </section>
                </template>

            </div>
        </div>

        <!-- CHEM LAB -->
        <div x-show="mode === 'Chem Lab'" class="space-y-14">

            <section class="space-y-6" x-data="chemLabApp()">
                <div class="flex items-center justify-between">
                    <h3 class="text-4xl font-bold italic tracking-tight text-amber-900">
                        <i class="fa-solid fa-flask mr-3"></i>Interactive Chemistry Lab
                    </h3>
                    <button @click="showLab = !showLab"
                        class="px-6 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition-all"
                        :class="showLab ? 'bg-amber-700 text-white' : 'bg-amber-100 text-amber-900'">
                        <i class="fa-solid" :class="showLab ? 'fa-chevron-up' : 'fa-flask-vial'"></i>
                        <span class="ml-2" x-text="showLab ? 'Close Lab' : 'Open Lab'"></span>
                    </button>
                </div>

                <div x-show="showLab" x-transition class="space-y-6">
                    <!-- Lab Mode Switcher -->
                    <div class="flex gap-4 flex-wrap">
                        <button @click="labMode = 'experiment'"
                            :class="labMode === 'experiment' ? 'bg-amber-700 text-white' : 'bg-white text-amber-900'"
                            class="px-6 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition-all">
                            <i class="fa-solid fa-flask mr-2"></i>Experiment Bench
                        </button>
                        <button @click="labMode = 'periodic'"
                            :class="labMode === 'periodic' ? 'bg-amber-700 text-white' : 'bg-white text-amber-900'"
                            class="px-6 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition-all">
                            <i class="fa-solid fa-atom mr-2"></i>Periodic Table
                        </button>
                        <button @click="labMode = 'reactions'"
                            :class="labMode === 'reactions' ? 'bg-amber-700 text-white' : 'bg-white text-amber-900'"
                            class="px-6 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition-all">
                            <i class="fa-solid fa-book-open mr-2"></i>Reaction Library
                        </button>
                    </div>

                    <!-- EXPERIMENT BENCH -->
                    <div x-show="labMode === 'experiment'" class="bg-white rounded-3xl shadow-2xl p-8">
                        <div class="mb-6">
                            <h4 class="text-2xl font-bold text-amber-900 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-vials"></i> Virtual Lab Bench
                            </h4>
                            <p class="text-gray-600">Select chemicals from the cabinet and mix them to see reactions!</p>
                        </div>

                        <!-- Chemical Cabinet -->
                        <div class="mb-8">
                            <h5 class="font-bold text-amber-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-cabinet-filing"></i> Chemical Cabinet
                            </h5>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                <template x-for="chemical in chemicals" :key="chemical.formula">
                                    <button @click="addToBeaker(chemical)"
                                        class="p-4 rounded-xl shadow-md hover:shadow-xl hover:scale-105 transition-all text-center border-2"
                                        :class="chemical.color">
                                        <div class="text-2xl mb-2" x-text="chemical.icon"></div>
                                        <div class="font-bold text-sm" x-text="chemical.name"></div>
                                        <div class="text-xs opacity-75 font-mono" x-text="chemical.formula"></div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Lab Bench -->
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 border-4 border-amber-300">
                            <h5 class="font-bold text-amber-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-beaker"></i> Reaction Beaker
                            </h5>

                            <!-- Beaker Contents -->
                            <div class="bg-white rounded-xl p-6 mb-4 min-h-[200px] border-4 border-amber-200 relative overflow-hidden">
                                <div x-show="beakerContents.length === 0" class="text-center text-gray-400 py-12">
                                    <i class="fa-solid fa-flask text-6xl mb-4 opacity-20"></i>
                                    <p class="font-semibold">Empty - Add chemicals to begin</p>
                                </div>

                                <div x-show="beakerContents.length > 0" class="space-y-2">
                                    <template x-for="(item, idx) in beakerContents" :key="idx">
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50">
                                            <div class="flex items-center gap-3">
                                                <span class="text-2xl" x-text="item.icon"></span>
                                                <div>
                                                    <div class="font-bold" x-text="item.name"></div>
                                                    <div class="text-xs font-mono text-gray-600" x-text="item.formula"></div>
                                                </div>
                                            </div>
                                            <button @click="removeFromBeaker(idx)" class="text-red-600 hover:text-red-800">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- Reaction Animation -->
                                <div x-show="isReacting" class="absolute inset-0 flex items-center justify-center bg-white/90 backdrop-blur-sm">
                                    <div class="text-center animate-pulse">
                                        <i class="fa-solid fa-atom-simple text-6xl text-orange-600 animate-spin"></i>
                                        <p class="mt-4 font-bold text-amber-900">Reaction in progress...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4">
                                <button @click="performReaction()" :disabled="beakerContents.length < 2"
                                    class="flex-1 bg-gradient-to-r from-orange-600 to-orange-500 text-white py-4 rounded-xl font-bold hover:shadow-xl hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                    <i class="fa-solid fa-play mr-2"></i>Perform Reaction
                                </button>
                                <button @click="clearBeaker()"
                                    class="px-6 py-4 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all">
                                    <i class="fa-solid fa-trash mr-2"></i>Clear
                                </button>
                            </div>
                        </div>

                        <!-- Reaction Result -->
                        <div x-show="reactionResult" x-transition class="mt-6 p-6 rounded-xl" :class="reactionResult?.type === 'success' ? 'bg-green-50 border-2 border-green-300' : 'bg-red-50 border-2 border-red-300'">
                            <div class="flex items-start gap-4">
                                <i class="fa-solid text-3xl" :class="reactionResult?.type === 'success' ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600'"></i>
                                <div class="flex-1">
                                    <h5 class="font-bold text-lg mb-2" :class="reactionResult?.type === 'success' ? 'text-green-900' : 'text-red-900'" x-text="reactionResult?.title"></h5>
                                    <div x-show="reactionResult?.type === 'success'" class="space-y-2">
                                        <div class="bg-white p-4 rounded-lg font-mono text-lg font-bold text-center text-amber-900" x-html="reactionResult?.equation"></div>
                                        <p class="text-gray-700" x-text="reactionResult?.description"></p>
                                        <div class="flex gap-2 mt-3">
                                            <span class="px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full" x-text="reactionResult?.reactionType"></span>
                                            <span class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded-full" x-text="'Grade ' + reactionResult?.grade"></span>
                                        </div>
                                    </div>
                                    <p x-show="reactionResult?.type !== 'success'" class="text-gray-700" x-text="reactionResult?.message"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PERIODIC TABLE -->
                    <div x-show="labMode === 'periodic'" class="bg-white rounded-3xl shadow-2xl p-8">
                        <h4 class="text-2xl font-bold text-amber-900 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-atom"></i> Interactive Periodic Table
                        </h4>

                        <!-- Search and Filter -->
                        <div class="mb-6 space-y-4">
                            <input x-model="searchElement" @input="filterElements()"
                                placeholder="Search by name, symbol, or atomic number..."
                                class="w-full px-6 py-4 rounded-xl border-2 border-amber-200 focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10 transition-all outline-none font-semibold">

                            <div class="flex flex-wrap gap-2">
                                <button @click="categoryFilter = ''; filterElements()"
                                    :class="categoryFilter === '' ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition">All</button>
                                <template x-for="cat in categories" :key="cat">
                                    <button @click="categoryFilter = cat; filterElements()"
                                        :class="categoryFilter === cat ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-700'"
                                        class="px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition capitalize"
                                        x-text="cat.replace('-', ' ')"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Elements Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                            <template x-for="element in filteredElements" :key="element.number">
                                <button @click="selectElement(element)"
                                    :class="getElementColor(element.category)"
                                    class="relative p-3 rounded-xl shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 text-left border-2">
                                    <div class="text-xs font-bold opacity-70" x-text="element.number"></div>
                                    <div class="text-2xl font-black my-1" x-text="element.symbol"></div>
                                    <div class="text-xs font-semibold truncate" x-text="element.name"></div>
                                    <div class="text-xs opacity-70" x-text="element.atomicMass"></div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Element Detail Modal with 3D Electron Structure -->
                    <div x-show="selectedElement" x-transition.opacity
                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                        @click="selectedElement = null" style="display: none;">
                        <div @click.stop class="bg-white rounded-3xl p-8 max-w-4xl w-full shadow-2xl max-h-[90vh] overflow-y-auto" x-show="selectedElement">
                            <template x-if="selectedElement">
                                <div>
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <div class="text-sm text-gray-600 mb-1">Atomic Number <span class="font-bold text-amber-700" x-text="selectedElement.number"></span></div>
                                            <h3 class="text-4xl md:text-5xl font-black text-amber-900 mb-2" x-text="selectedElement.name"></h3>
                                            <div class="text-2xl font-bold text-amber-700" x-text="selectedElement.symbol"></div>
                                        </div>
                                        <button @click="selectedElement = null" class="text-gray-400 hover:text-gray-600 text-2xl">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- 2D Electron Structure (BYJU's Style with Revolving Electrons) -->
                                    <div class="bg-gradient-to-br from-blue-50 via-purple-50 to-orange-50 rounded-3xl p-10 mb-6 flex flex-col items-center justify-center min-h-[450px] border-3 border-purple-200 shadow-2xl space-y-6">
                                        <h4 class="text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-orange-600 bg-clip-text text-transparent">Electron Configuration Structure</h4>

                                        <div class="electron-structure-container" :data-element="selectedElement.number">
                                            <!-- Element Symbol (Center) -->
                                            <div class="nucleus">
                                                <div class="nucleus-core">
                                                    <span class="text-white font-bold text-lg" x-text="selectedElement.symbol"></span>
                                                </div>
                                            </div>

                                            <!-- Electron Shells with orbiting electrons -->
                                            <template x-for="shell in getElectronShells(selectedElement)" :key="shell.n">
                                                <div class="shell-container" :style="`--shell-radius: ${shell.radius}px; --shell-n: ${shell.n}; --shell-speed: ${(8 - shell.n * 0.5)}s;`">
                                                    <!-- Orbital Ring (rotates) -->
                                                    <div class="shell-ring" :class="shell.isValence ? 'shell-ring-valence' : 'shell-ring-inner'"></div>

                                                    <!-- Shell Label -->
                                                    <div class="shell-label" x-text="`${shell.label}`"></div>

                                                    <!-- Electron Count Badge -->
                                                    <div class="electron-count-badge" x-text="shell.count + ' e⁻'"></div>

                                                    <!-- Electrons orbiting this shell -->
                                                    <div class="electrons-orbit-group">
                                                        <template x-for="(electron, idx) in shell.electrons" :key="idx">
                                                            <div class="electron-orbit-wrapper" :style="`--orbit-angle: ${electron * 360 / shell.count}deg;`">
                                                                <div class="electron-dot" :class="shell.isValence ? 'electron-dot-valence' : 'electron-dot-inner'"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Legend & Info -->
                                        <div class="flex flex-col gap-4 items-center">
                                            <div class="flex gap-8 justify-center text-sm font-semibold">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-lg animate-pulse"></div>
                                                    <span class="text-blue-800">Inner Electrons</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="w-3 h-3 rounded-full bg-amber-500 shadow-lg animate-pulse"></div>
                                                    <span class="text-amber-800">Valence Electrons</span>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-600 italic" x-show="selectedElement">
                                                <span x-text="`${selectedElement.name} (${selectedElement.symbol}) - Atomic Number: ${selectedElement.number}`"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Element Properties -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Atomic Mass</div>
                                            <div class="text-2xl font-black text-amber-900" x-text="selectedElement.atomicMass"></div>
                                        </div>
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Category</div>
                                            <div class="text-sm font-bold text-amber-900 capitalize" x-text="selectedElement.category.replace('-', ' ')"></div>
                                        </div>
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Group</div>
                                            <div class="text-2xl font-black text-amber-900" x-text="selectedElement.group"></div>
                                        </div>
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Period</div>
                                            <div class="text-2xl font-black text-amber-900" x-text="selectedElement.period"></div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-xl border-2 border-gray-200">
                                        <div class="text-xs text-gray-700 font-bold uppercase mb-2">Electron Configuration</div>
                                        <div class="text-lg font-mono font-semibold text-gray-900" x-html="selectedElement.electronConfig"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Shell Distribution</div>
                                            <div class="text-sm font-mono font-semibold text-amber-900" x-text="getShellSummary(selectedElement)"></div>
                                        </div>
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Valence Shell</div>
                                            <div class="text-2xl font-black text-amber-900" x-text="getValenceShellLabel(selectedElement)"></div>
                                        </div>
                                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                                            <div class="text-xs text-amber-700 font-bold uppercase mb-1">Valence Electrons</div>
                                            <div class="text-2xl font-black text-amber-900" x-text="getValenceElectrons(selectedElement)"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- REACTION LIBRARY -->
                    <div x-show="labMode === 'reactions'" class="bg-white rounded-3xl shadow-2xl p-8">
                        <h4 class="text-2xl font-bold text-amber-900 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-book-open"></i> Reaction Library (105+ Reactions)
                        </h4>

                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <input x-model="searchReaction" @input="filterReactions()"
                                placeholder="Search reactions..."
                                class="w-full px-6 py-4 rounded-xl border-2 border-amber-200 focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10 transition-all outline-none font-semibold">

                            <select x-model="gradeFilter" @change="filterReactions()"
                                class="w-full px-6 py-4 rounded-xl border-2 border-amber-200 focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10 transition-all outline-none font-semibold">
                                <option value="">All Grades</option>
                                <option value="5-7">Grades 5-7</option>
                                <option value="7-9">Grades 7-9</option>
                                <option value="8-10">Grades 8-10</option>
                                <option value="9-10">Grades 9-10</option>
                                <option value="10">Grade 10</option>
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            <button @click="typeFilter = ''; filterReactions()"
                                :class="typeFilter === '' ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition">All Types</button>
                            <template x-for="type in reactionTypes" :key="type">
                                <button @click="typeFilter = type; filterReactions()"
                                    :class="typeFilter === type ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition"
                                    x-text="type"></button>
                            </template>
                        </div>

                        <!-- Reactions List -->
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            <template x-for="reaction in filteredReactions" :key="reaction.id">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-all border-2 border-amber-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h5 class="text-xl font-bold text-amber-900 mb-2" x-text="reaction.name"></h5>
                                            <div class="flex gap-2 items-center flex-wrap">
                                                <span class="px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full" x-text="reaction.type"></span>
                                                <span class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded-full" x-text="'Grade ' + reaction.grade"></span>
                                            </div>
                                        </div>
                                        <button @click="tryReaction(reaction)" class="px-4 py-2 bg-amber-700 text-white rounded-lg font-bold hover:bg-amber-800 transition">
                                            <i class="fa-solid fa-flask mr-2"></i>Try It
                                        </button>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg mb-3 font-mono text-base font-semibold text-center text-amber-900 border-2 border-amber-200" x-html="reaction.equation"></div>

                                    <p class="text-gray-700" x-text="reaction.description"></p>
                                </div>
                            </template>
                        </div>

                        <div x-show="filteredReactions.length === 0" class="text-center py-12">
                            <i class="fa-solid fa-search text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold">No reactions found</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- LIBRARY -->
        <div x-show="mode === 'Library'" class="space-y-14">

            <!-- Library Resources -->
            <template x-for="(files, grade) in libraryResources" :key="grade">
                <section class="space-y-6">

                    <!-- Grade Header -->
                    <h3 class="text-4xl font-bold italic tracking-tight text-amber-900">
                        Grade <span x-text="grade"></span>
                    </h3>

                    <!-- Files Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <template x-for="(file, i) in files" :key="i">
                            <div
                                class="group relative bg-gray-50 rounded-3xl p-6 shadow
                               flex flex-col justify-between
                               transition-all duration-300 ease-out
                               hover:-translate-y-2 hover:scale-[1.02] hover:shadow-2xl hover:bg-white">

                                <!-- File Name -->
                                <h5 class="italic font-bold mb-6 text-sm text-stone-800
                                   transition-colors duration-300
                                   group-hover:text-orange-600"
                                    x-text="file">
                                </h5>

                                <!-- View PDF Button -->
                                <a :href="`/content/${encodeURIComponent(file)}`" target="_blank"
                                    rel="noopener noreferrer"
                                    class="relative text-xs font-bold uppercase text-orange-600
                                   after:absolute after:left-0 after:-bottom-1
                                   after:w-0 after:h-[2px] after:bg-orange-600
                                   after:transition-all after:duration-300
                                   group-hover:after:w-full">
                                    View PDF →
                                </a>
                            </div>
                        </template>
                    </div>

                </section>
            </template>

        </div>


        <!-- AI LAB -->
        <div x-show="mode === 'AI Lab'" x-transition.opacity.duration.500ms class="space-y-14">

            <!-- FORM -->
            <div x-show="!lesson" x-transition
                class="relative overflow-hidden bg-gradient-to-br from-black via-stone-950 to-black
               text-white p-14 rounded-[4rem] space-y-10 shadow-2xl">

                <!-- Glow -->
                <div class="absolute -top-32 -right-32 w-96 h-96 bg-amber-600/30 rounded-full blur-3xl animate-[pulse_8s_ease-in-out_infinite]"></div>

                <div class="relative space-y-10">

                    <!-- Title -->
                    <div class="space-y-2">
                        <h3 class="text-6xl font-bold italic tracking-tight text-white">AI Lesson Lab</h3>
                        <p class="text-amber-300 text-sm font-semibold">
                            Personalized learning crafted instantly for your academic needs.
                        </p>
                    </div>

                    <!-- Grade Selector -->
                    <div class="space-y-3">
                        <p class="uppercase text-xs tracking-widest text-white/50">Select Grade</p>

                        <div class="grid grid-cols-5 gap-4">
                            <template x-for="g in [3,4,5,6,7,8,9,10]" :key="g">
                                <button @click="setGrade(g)"
                                    :class="selectedGrade === g ?
                                        'bg-gradient-to-r from-amber-700 to-amber-600 scale-105' :
                                        'bg-white/10 hover:bg-white/20'"
                                    class="rounded-2xl py-4 font-black transition-all duration-300">
                                    G-<span x-text="g"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="space-y-4">
                        <p class="uppercase text-xs tracking-widest text-amber-300 font-semibold">Select Subject</p>

                        <!-- Subject Pills Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="s in subjects" :key="s.name">
                                <button @click="selectedSubject = s.name"
                                    :class="{
                                        'from-amber-600 to-amber-500 border-amber-400 shadow-lg shadow-amber-600/50 scale-105': selectedSubject === s.name,
                                        'from-amber-700/30 to-amber-600/20 border-amber-600/40 hover:border-amber-500 hover:from-amber-700/40 hover:to-amber-600/30': selectedSubject !== s.name
                                    }"
                                    class="p-4 rounded-xl bg-gradient-to-br border-2 text-white font-bold italic tracking-tight
                                    transition-all duration-300 transform hover:scale-105 active:scale-95
                                    hover:shadow-lg hover:shadow-amber-600/30 relative overflow-hidden group">

                                    <!-- Animated background -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                    <span class="relative z-10" x-text="s.name"></span>

                                    <!-- Selection checkmark animation -->
                                    <div x-show="selectedSubject === s.name"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="scale-0 -rotate-12"
                                        x-transition:enter-end="scale-100 rotate-0"
                                        class="absolute top-2 right-2 text-emerald-400 text-lg">
                                        ✓
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Topic -->
                    <div class="space-y-3">
                        <p class="uppercase text-xs tracking-widest text-white/50">Topic</p>

                        <input x-model="selectedTopic"
                            class="w-full p-5 rounded-2xl bg-white/10 focus:outline-none focus:ring-2 focus:ring-amber-600"
                            placeholder="e.g. Photosynthesis, Algebraic Expressions..." />
                    </div>

                    <!-- Generate Button -->
                    <button @click="generateLesson" :disabled="loading"
                        class="w-full bg-gradient-to-r from-amber-700 to-amber-600 text-white py-6 rounded-2xl font-black uppercase
                       hover:scale-[1.02] transition-all duration-300
                       disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!loading">Generate Lesson</span>
                        <span x-show="loading" class="animate-pulse">Generating AI Content…</span>
                    </button>
                </div>
            </div>

            <!-- LESSON OUTPUT -->
            <template x-if="lesson">
                <div x-transition x-cloak
                    class="bg-gradient-to-br from-black via-stone-900 to-black
               text-white p-6 sm:p-10 md:p-14 lg:p-20 rounded-2xl sm:rounded-3xl md:rounded-[4rem] space-y-8 sm:space-y-10 md:space-y-14 shadow-2xl">

                    <!-- Back -->
                    <button @click="resetLesson" class="text-amber-400 text-xs uppercase hover:underline font-semibold">
                        ← Create another lesson
                    </button>

                    <!-- Lesson Title -->
                    <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold italic leading-tight text-white" x-text="lesson.title"></h2>
                    <p class="mt-2 md:mt-3 text-base sm:text-lg md:text-xl text-amber-200 font-semibold" x-text="lesson.description"></p>

                    <!-- Learning Objectives -->
                    <div class="mt-6 sm:mt-8 md:mt-10">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold italic mb-4 md:mb-6 tracking-tight text-white">Learning Objectives</h3>

                        <ul class="space-y-2 md:space-y-3">
                            <template x-for="(obj, index) in lesson.objectives" :key="'obj-' + index">
                                <li class="flex gap-2 md:gap-3 text-base sm:text-lg md:text-xl text-white/90">
                                    <span class="text-purple-400 font-black">•</span>
                                    <span x-text="obj"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Lesson Content -->
                    <div class="mt-8 sm:mt-10 md:mt-12 space-y-6 md:space-y-8">
                        <p class="text-sm sm:text-base md:text-lg leading-relaxed whitespace-pre-wrap text-white/90 font-medium" x-text="lesson.content"></p>

                        <p class="text-base sm:text-lg md:text-xl leading-relaxed whitespace-pre-wrap text-white/90"
                            x-text="lesson.nepal_context"></p>
                    </div>

                    <!-- Key Points -->
                    <div class="mt-8 sm:mt-10 md:mt-12">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold italic mb-4 md:mb-6 tracking-tight text-white">Key Points</h3>

                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <template x-for="(point, index) in lesson.key_points" :key="'kp-' + index">
                                <li class="bg-white/5 border border-white/10 rounded-lg md:rounded-xl p-3 md:p-4 text-sm sm:text-base md:text-lg text-white/90">
                                    <span x-text="point"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- ANALOGY -->
                    <div class="mt-8 sm:mt-10 md:mt-12 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6">
                        <h3 class="text-2xl sm:text-3xl font-bold italic mb-3 md:mb-4 tracking-tight text-amber-300">Analogy</h3>
                        <p class="text-sm sm:text-base md:text-lg text-amber-100 leading-relaxed font-medium" x-text="lesson.analogy"></p>
                    </div>

                    <!-- PRACTICE QUESTIONS -->
                    <div class="mt-10 sm:mt-12 md:mt-14">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold italic mb-6 md:mb-8 tracking-tight text-white">Practice Questions</h3>

                        <template x-for="(q, qIndex) in lesson.questions" :key="'q-' + qIndex">
                            <div class="mb-6 md:mb-8 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6 space-y-4 md:space-y-5">

                                <!-- Question -->
                                <p class="text-lg sm:text-xl font-bold italic text-white" x-text="q.question"></p>

                                <!-- Options -->
                                <ul class="space-y-1.5 md:space-y-2 pl-3 md:pl-4">
                                    <template x-for="(opt, oIndex) in q.options" :key="'opt-' + qIndex + '-' + oIndex">
                                        <li class="text-white/80 flex gap-2 font-medium text-sm sm:text-base">
                                            <span>•</span>
                                            <span x-text="opt"></span>
                                        </li>
                                    </template>
                                </ul>

                                <!-- Correct Answer -->
                                <div class="mt-3 md:mt-4 pt-3 md:pt-4 border-t border-white/10">
                                    <p class="text-[10px] md:text-xs uppercase tracking-widest text-emerald-400 font-bold mb-2">
                                        Correct Answer
                                    </p>
                                    <p class="text-base sm:text-lg text-amber-200 font-bold italic" x-text="typeof q.correct_answer === 'number' ? q.options[q.correct_answer] : q.correct_answer"></p>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- FOLLOW-UP ACTIVITIES -->
                    <div class="mt-10 sm:mt-12 md:mt-14">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold italic mb-4 md:mb-6 tracking-tight text-white">Follow-Up Activities</h3>

                        <ul class="space-y-2 md:space-y-3">
                            <template x-for="(item, index) in lesson.follow_up_suggestions" :key="'fu-' + index">
                                <li class="text-base sm:text-lg text-amber-100 flex gap-2 md:gap-3 font-medium leading-relaxed">
                                    <span class="text-emerald-400 font-bold">→</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- FOLLOW UPS -->
                    <div class="space-y-8 border-t border-white/10 pt-10">

                        <h4 class="uppercase text-xs tracking-widest text-white/50">
                            Ask Follow-Up Questions
                        </h4>

                        <template x-for="(c,i) in chatHistory" :key="i">
                            <div class="space-y-2">
                                <p class="font-bold text-orange-400">
                                    Q: <span class="text-white" x-text="c.q"></span>
                                </p>
                                <p class="italic text-white/80">
                                    A: <span x-text="c.a"></span>
                                </p>
                            </div>
                        </template>

                        <form @submit.prevent="handleFollowUp" class="flex gap-4">
                            <input x-model="followUpQuestion" :disabled="loadingFollowUp"
                                class="flex-1 p-5 rounded-2xl bg-white/10
                  focus:outline-none focus:ring-2 focus:ring-orange-600
                  disabled:opacity-50 disabled:cursor-not-allowed"
                                placeholder="Ask something deeper..." />
                            <button :disabled="loadingFollowUp || !followUpQuestion"
                                class="bg-orange-600 px-8 rounded-2xl font-bold hover:scale-105 transition
               disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loadingFollowUp">Ask</span>
                                <span x-show="loadingFollowUp" class="animate-pulse">Loading…</span>
                            </button>
                        </form>
                    </div>

                </div>
            </template>

        </div>

    </div>

    <!-- ALPINE COMPONENT -->
    <script>
        function eduSyncApp() {
            return {
                mode: 'Hub',
                selectedGrade: '',
                selectedSubject: '',
                selectedTopic: '',
                lesson: null,
                loading: false,
                followUpQuestion: '',
                chatHistory: [],
                loadingFollowUp: false,

                subjects: [{
                        name: 'Science',
                        icon: 'fa-microscope',
                        color: 'bg-emerald-600',
                        count: '42 Lessons'
                    },
                    {
                        name: 'Math',
                        icon: 'fa-calculator',
                        color: 'bg-blue-600',
                        count: '38 Lessons'
                    },
                    {
                        name: 'English',
                        icon: 'fa-language',
                        color: 'bg-rose-600',
                        count: '25 Lessons'
                    },
                    {
                        name: 'Social Studies',
                        icon: 'fa-landmark',
                        color: 'bg-yellow-600',
                        count: '30 Lessons'
                    },
                    {
                        name: 'Health',
                        icon: 'fa-heart-pulse',
                        color: 'bg-pink-600',
                        count: '15 Lessons'
                    },
                    {
                        name: 'Computer Science',
                        icon: 'fa-computer',
                        color: 'bg-purple-600',
                        count: '20 Lessons'
                    },
                ],

                libraryResources: {
                    3: [
                        'My Mathematics Grade 3.pdf',
                        'Nepali Grade 3.pdf',
                        'Our Serofero Grade 3.pdf'
                    ],
                    4: [
                        'English Grade 4.pdf',
                        'Mathematics Grade 4.pdf',
                        'Nepali Grade 4.pdf',
                        'Health, Physical and Creative Arts Grade 4.pdf',
                        'Science and Technology Grade 4.pdf',
                        'Social Studies and Human Value Education Grade 4.pdf'
                    ],
                    5: [
                        'English Grade 5.pdf',
                        'Mathematics Grade 5.pdf',
                        'Nepali Grade 5.pdf',
                        'Health, Physical and Creative Arts Grade 5.pdf',
                        'Science and Technology Grade 5.pdf',
                        'Social Studies Grade 5.pdf'
                    ],
                    6: [
                        'English Grade 6.pdf',
                        'My Mathematics Grade 6.pdf',
                        'Nepali Grade 6.pdf',
                        'Health, Physical and Creative Arts Grade 6.pdf',
                        'Science and Technology Grade 6.pdf',
                        'Social Studies and Human Value Education Grade 6.pdf'
                    ],
                    7: [
                        'English Grade 7.pdf',
                        'Mathematics Grade 7.pdf',
                        'Nepali Grade 7.pdf',
                        'Health, Physical and Creative Arts Grade 7.pdf',
                        'Science and Technology Grade 7.pdf',
                        'Social Studies and Human Value Education Grade 7.pdf'
                    ],
                    8: [
                        'English Grade 8.pdf',
                        'Mathematics Grade 8.pdf',
                        'Nepali Grade 8.pdf',
                        'Health, Physical and Creative Art Grade 8.pdf',
                        'Science and Technology Grade 8.pdf',
                        'Social Studies and Human Value Education Grade 8.pdf'
                    ],
                    9: [
                        'Computer Science Grade 9.pdf',
                        'English Grade 9.pdf',
                        'Mathematics Grade 9 Part 1.pdf',
                        'Mathematics Grade 9 Part 2.pdf',
                        'Nepali Grade 9.pdf',
                        'Science and Technology Grade 9.pdf',
                        'Social Studies Grade 9.pdf'
                    ],
                    10: [
                        'English Grade 10.pdf',
                        'Mathematics Class 10 Part 1.pdf',
                        'Mathematics Class 10 Part 2.pdf',
                        'Nepali Grade 10.pdf',
                        'Science and Technology Grade 10.pdf',
                        'Social Studies Grade 10.pdf'
                    ]
                },

                setMode(m) {
                    this.mode = m
                },
                setGrade(g) {
                    this.selectedGrade = g
                },

                async generateLesson() {
                    if (!this.selectedTopic) return;
                    this.loading = true;

                    const res = await fetch('/education/generate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')
                                .content
                        },
                        body: JSON.stringify({
                            grade: this.selectedGrade,
                            subject: this.selectedSubject,
                            topic: this.selectedTopic
                        })
                    });

                    const data = await res.json();
                    this.lesson = data.lesson;
                    this.loading = false;
                },

                async handleFollowUp() {
                    if (!this.followUpQuestion || !this.lesson) return;

                    const q = this.followUpQuestion;
                    this.followUpQuestion = '';
                    this.loadingFollowUp = true; // start loading

                    try {
                        const res = await fetch('/education/followup', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                context: this.lesson.content,
                                question: q
                            })
                        });

                        const data = await res.json();
                        this.chatHistory.push({
                            q,
                            a: data.answer
                        });
                    } catch (err) {
                        console.error('Error:', err);
                    } finally {
                        this.loadingFollowUp = false; // end loading
                    }
                },

                resetLesson() {
                    this.lesson = null;
                    this.chatHistory = [];
                },

                downloadResource(f) {
                    window.location.href = `/content/${encodeURIComponent(f)}`;
                }
            }
        }

        // hubAI() function
        function hubAI() {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            return {
                grade: '',
                topic: '',
                quiz: [],
                cards: [],
                loadingQuiz: false,
                loadingFlash: false,

                // Quiz state
                currentQuestion: 0,
                userAnswers: {},
                quizCompleted: false,

                // Flashcard state
                flippedCards: {},

                async loadQuiz() {
                    if (!this.grade || !this.topic) return;

                    this.loadingQuiz = true;
                    this.quiz = [];
                    this.resetQuiz();

                    try {
                        const r = await fetch('/education/quiz', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                grade: this.grade,
                                topic: this.topic
                            })
                        });

                        const data = await r.json();
                        this.quiz = data.quiz || [];
                    } catch (error) {
                        console.error('Error loading quiz:', error);
                    } finally {
                        this.loadingQuiz = false;
                    }
                },

                async loadFlash() {
                    if (!this.grade || !this.topic) return;

                    this.loadingFlash = true;
                    this.cards = [];
                    this.flippedCards = {};

                    try {
                        const r = await fetch('/education/flash', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                grade: this.grade,
                                topic: this.topic
                            })
                        });

                        const data = await r.json();
                        this.cards = data.cards || [];
                    } catch (error) {
                        console.error('Error loading flashcards:', error);
                    } finally {
                        this.loadingFlash = false;
                    }
                },

                // Quiz Methods
                getCorrectAnswerIndex(question) {
                    // Handle new format: correct_answer as index (0, 1, 2, 3)
                    if (typeof question.correct_answer === 'number') {
                        return question.correct_answer;
                    }
                    // Fallback for old format: letter answer (A, B, C, D)
                    const letterToIndex = { 'A': 0, 'B': 1, 'C': 2, 'D': 3 };
                    return letterToIndex[question.answer?.toUpperCase()] ?? -1;
                },

                selectAnswer(questionIndex, optionIndex, answer) {
                    // Prevent changing answer after selection
                    if (this.userAnswers[questionIndex] !== undefined) return;

                    this.userAnswers[questionIndex] = {
                        selectedIndex: optionIndex,
                        selectedValue: answer
                    };
                },

                nextQuestion() {
                    if (this.currentQuestion < this.quiz.length - 1) {
                        this.currentQuestion++;
                    }
                },

                previousQuestion() {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                    }
                },

                completeQuiz() {
                    this.quizCompleted = true;
                },

                calculateScore() {
                    let correct = 0;
                    this.quiz.forEach((q, i) => {
                        const correctIndex = this.getCorrectAnswerIndex(q);
                        if (this.userAnswers[i]?.selectedIndex === correctIndex) {
                            correct++;
                        }
                    });
                    return correct;
                },

                resetQuiz() {
                    this.currentQuestion = 0;
                    this.userAnswers = {};
                    this.quizCompleted = false;
                },

                // Flashcard Methods
                flipCard(index) {
                    this.flippedCards[index] = !this.flippedCards[index];
                },

                shuffleCards() {
                    // Fisher-Yates shuffle algorithm
                    for (let i = this.cards.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [this.cards[i], this.cards[j]] = [this.cards[j], this.cards[i]];
                    }
                    this.flippedCards = {};
                }
            }
        }

        // chemLabApp() function
        function chemLabApp() {
            return {
                showLab: false,
                labMode: 'experiment',
                elements: [],
                reactions: [],
                filteredElements: [],
                filteredReactions: [],
                searchElement: '',
                searchReaction: '',
                categoryFilter: '',
                typeFilter: '',
                gradeFilter: '',
                selectedElement: null,
                beakerContents: [],
                reactionResult: null,
                isReacting: false,
                categories: [
                    'alkali-metal', 'alkaline-earth', 'transition', 'post-transition',
                    'metalloid', 'nonmetal', 'halogen', 'noble-gas', 'lanthanide', 'actinide'
                ],
                reactionTypes: [],
                chemicals: [
                    { name: 'Hydrogen', formula: 'H₂', icon: '💨', color: 'bg-blue-100 border-blue-300 text-blue-900' },
                    { name: 'Oxygen', formula: 'O₂', icon: '💨', color: 'bg-sky-100 border-sky-300 text-sky-900' },
                    { name: 'Water', formula: 'H₂O', icon: '💧', color: 'bg-cyan-100 border-cyan-300 text-cyan-900' },
                    { name: 'Carbon Dioxide', formula: 'CO₂', icon: '☁️', color: 'bg-gray-100 border-gray-300 text-gray-900' },
                    { name: 'Sodium', formula: 'Na', icon: '⚛️', color: 'bg-yellow-100 border-yellow-300 text-yellow-900' },
                    { name: 'Chlorine', formula: 'Cl₂', icon: '☣️', color: 'bg-green-100 border-green-300 text-green-900' },
                    { name: 'Hydrochloric Acid', formula: 'HCl', icon: '🧪', color: 'bg-red-100 border-red-300 text-red-900' },
                    { name: 'Sodium Hydroxide', formula: 'NaOH', icon: '🧫', color: 'bg-purple-100 border-purple-300 text-purple-900' },
                    { name: 'Magnesium', formula: 'Mg', icon: '✨', color: 'bg-gray-200 border-gray-400 text-gray-900' },
                    { name: 'Iron', formula: 'Fe', icon: '🔩', color: 'bg-orange-100 border-orange-400 text-orange-900' },
                    { name: 'Copper Sulfate', formula: 'CuSO₄', icon: '💎', color: 'bg-blue-200 border-blue-400 text-blue-900' },
                    { name: 'Zinc', formula: 'Zn', icon: '⚙️', color: 'bg-slate-100 border-slate-300 text-slate-900' },
                    { name: 'Sulfuric Acid', formula: 'H₂SO₄', icon: '⚗️', color: 'bg-yellow-200 border-yellow-400 text-yellow-900' },
                    { name: 'Calcium Carbonate', formula: 'CaCO₃', icon: '🪨', color: 'bg-stone-100 border-stone-300 text-stone-900' },
                    { name: 'Silver Nitrate', formula: 'AgNO₃', icon: '💫', color: 'bg-gray-50 border-gray-300 text-gray-800' },
                    { name: 'Ammonia', formula: 'NH₃', icon: '💨', color: 'bg-teal-100 border-teal-300 text-teal-900' },
                    { name: 'Methane', formula: 'CH₄', icon: '🔥', color: 'bg-orange-50 border-orange-300 text-orange-900' },
                    { name: 'Nitrogen', formula: 'N₂', icon: '💨', color: 'bg-indigo-100 border-indigo-300 text-indigo-900' }
                ],

                async init() {
                    // Load periodic table data
                    const elementsRes = await fetch('/json/periodic-table.json');
                    this.elements = await elementsRes.json();
                    this.filteredElements = this.elements;

                    // Load reactions data
                    const reactionsRes = await fetch('/json/chemical-reactions.json');
                    this.reactions = await reactionsRes.json();
                    this.filteredReactions = this.reactions;

                    // Extract unique reaction types
                    this.reactionTypes = [...new Set(this.reactions.map(r => r.type))];
                },

                addToBeaker(chemical) {
                    if (this.beakerContents.length >= 4) {
                        alert('Beaker is full! Clear it first.');
                        return;
                    }
                    this.beakerContents.push({ ...chemical });
                    this.reactionResult = null;
                },

                removeFromBeaker(index) {
                    this.beakerContents.splice(index, 1);
                    this.reactionResult = null;
                },

                clearBeaker() {
                    this.beakerContents = [];
                    this.reactionResult = null;
                },

                async performReaction() {
                    if (this.beakerContents.length < 2) return;

                    this.isReacting = true;
                    this.reactionResult = null;

                    // Simulate reaction time
                    await new Promise(resolve => setTimeout(resolve, 2000));

                    const formulas = this.beakerContents.map(c => c.formula).sort().join(' + ');

                    // Check for known reactions
                    const knownReaction = this.findMatchingReaction(formulas);

                    if (knownReaction) {
                        this.reactionResult = {
                            type: 'success',
                            title: '✅ Reaction Successful!',
                            equation: knownReaction.equation,
                            description: knownReaction.description,
                            reactionType: knownReaction.type,
                            grade: knownReaction.grade
                        };
                    } else {
                        this.reactionResult = {
                            type: 'error',
                            title: '❌ No Reaction Occurred',
                            message: 'These chemicals don\'t react under normal conditions, or the reaction is not in our database. Try different combinations!'
                        };
                    }

                    this.isReacting = false;
                },

                findMatchingReaction(formulas) {
                    // Simplified matching - check if formulas appear in equation
                    const parts = formulas.split(' + ');

                    return this.reactions.find(r => {
                        const equation = r.equation.toLowerCase();
                        return parts.every(part => equation.includes(part.toLowerCase()));
                    });
                },

                tryReaction(reaction) {
                    // Switch to experiment mode and auto-fill relevant chemicals
                    this.labMode = 'experiment';
                    this.clearBeaker();

                    alert(`💡 Try mixing the chemicals from this reaction: ${reaction.equation}\nExperiment mode is now ready!`);
                },

                filterElements() {
                    this.filteredElements = this.elements.filter(el => {
                        const searchMatch = !this.searchElement ||
                            el.name.toLowerCase().includes(this.searchElement.toLowerCase()) ||
                            el.symbol.toLowerCase().includes(this.searchElement.toLowerCase()) ||
                            el.number.toString().includes(this.searchElement);

                        const categoryMatch = !this.categoryFilter || el.category === this.categoryFilter;

                        return searchMatch && categoryMatch;
                    });
                },

                filterReactions() {
                    this.filteredReactions = this.reactions.filter(r => {
                        const searchMatch = !this.searchReaction ||
                            r.name.toLowerCase().includes(this.searchReaction.toLowerCase()) ||
                            r.equation.toLowerCase().includes(this.searchReaction.toLowerCase()) ||
                            r.type.toLowerCase().includes(this.searchReaction.toLowerCase());

                        const typeMatch = !this.typeFilter || r.type === this.typeFilter;
                        const gradeMatch = !this.gradeFilter || r.grade === this.gradeFilter;

                        return searchMatch && typeMatch && gradeMatch;
                    });
                },

                selectElement(element) {
                    this.selectedElement = element;
                },

                getElectronShells(element) {
                    if (!element) return [];

                    // Shell distribution using capacity per shell (2, 8, 18, 32, 32, 18, 8)
                    const shells = [];
                    let remaining = element.number;
                    const maxElectrons = [2, 8, 18, 32, 32, 18, 8];
                    const shellLabels = ['K', 'L', 'M', 'N', 'O', 'P', 'Q'];

                    for (let n = 1; n <= 7 && remaining > 0; n++) {
                        const electronsInShell = Math.min(remaining, maxElectrons[n - 1]);
                        const radius = 50 + (n * 30); // Increasing radius for each shell

                        shells.push({
                            n: n,
                            count: electronsInShell,
                            radius: radius,
                            label: shellLabels[n - 1],
                            speed: (6 + n * 2) + 's',
                            electrons: Array.from({length: electronsInShell}, (_, i) => i)
                        });

                        remaining -= electronsInShell;
                    }

                    if (shells.length > 0) {
                        shells[shells.length - 1].isValence = true;
                    }

                    return shells;
                },

                getShellSummary(element) {
                    const shells = this.getElectronShells(element);
                    return shells.map(shell => `${shell.label}${shell.count}`).join(' ');
                },

                getValenceShellLabel(element) {
                    const shells = this.getElectronShells(element);
                    return shells.length ? shells[shells.length - 1].label : '-';
                },

                getValenceElectrons(element) {
                    const shells = this.getElectronShells(element);
                    return shells.length ? shells[shells.length - 1].count : 0;
                },

                getElementColor(category) {
                    const colors = {
                        'alkali-metal': 'bg-red-100 text-red-900 border-red-400',
                        'alkaline-earth': 'bg-orange-100 text-orange-900 border-orange-400',
                        'transition': 'bg-yellow-100 text-yellow-900 border-yellow-400',
                        'post-transition': 'bg-green-100 text-green-900 border-green-400',
                        'metalloid': 'bg-teal-100 text-teal-900 border-teal-400',
                        'nonmetal': 'bg-blue-100 text-blue-900 border-blue-400',
                        'halogen': 'bg-indigo-100 text-indigo-900 border-indigo-400',
                        'noble-gas': 'bg-purple-100 text-purple-900 border-purple-400',
                        'lanthanide': 'bg-pink-100 text-pink-900 border-pink-400',
                        'actinide': 'bg-rose-100 text-rose-900 border-rose-400'
                    };
                    return colors[category] || 'bg-gray-100 text-gray-900 border-gray-400';
                }
            }
        }
    </script>

    <style>
        .animate-reveal {
            animation: reveal .4s ease
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1
            }
        }

        .rotate-y-180 {
            transform: rotateY(180deg);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <style>
        select option {
            color: black;
            background-color: white;
        }
    </style>

    <style>
        /* 2D Electron Structure with Orbiting Electrons */
        .electron-structure-container {
            position: relative;
            width: 340px;
            height: 340px;
            margin: 0 auto;
            filter: drop-shadow(0 0 30px rgba(147, 51, 234, 0.15));
        }

        .nucleus {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 20;
        }

        .nucleus-core {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f59e0b 0%, #dc2626 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-center;
            font-size: 20px;
            font-weight: 900;
            color: white;
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.8),
                        0 0 50px rgba(245, 158, 11, 0.4),
                        inset -2px -2px 8px rgba(139, 0, 0, 0.3);
            animation: nucleusPulseEnhanced 2.5s ease-in-out infinite;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        @keyframes nucleusPulseEnhanced {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 25px rgba(245, 158, 11, 0.8), 0 0 50px rgba(245, 158, 11, 0.4);
            }
            50% {
                transform: scale(1.15);
                box-shadow: 0 0 40px rgba(245, 158, 11, 1), 0 0 80px rgba(245, 158, 11, 0.6);
            }
        }

        .shell-container {
            position: absolute;
            top: 50%;
            left: 50%;
            width: calc(var(--shell-radius) * 2);
            height: calc(var(--shell-radius) * 2);
            transform: translate(-50%, -50%);
        }

        .shell-ring {
            position: absolute;
            inset: 0;
            border: 2px solid rgba(59, 130, 246, 0.4);
            border-radius: 50%;
            animation: rotateRingSmooth var(--shell-speed) linear infinite;
        }

        .shell-ring-inner {
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.2);
        }

        .shell-ring-valence {
            border-color: rgba(245, 158, 11, 0.7);
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.3), inset 0 0 10px rgba(245, 158, 11, 0.1);
        }

        @keyframes rotateRingSmooth {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .shell-label {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: 800;
            color: #1e40af;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(219, 234, 254, 0.98) 100%);
            padding: 4px 10px;
            border-radius: 20px;
            border: 2px solid rgba(59, 130, 246, 0.6);
            white-space: nowrap;
            z-index: 15;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            letter-spacing: 0.5px;
        }

        .electron-count-badge {
            position: absolute;
            bottom: -42px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            font-weight: 800;
            color: #dc2626;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(254, 242, 242, 0.98) 100%);
            padding: 4px 10px;
            border-radius: 20px;
            border: 2px solid rgba(220, 38, 38, 0.5);
            z-index: 15;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
            letter-spacing: 0.5px;
        }

        /* Electrons Orbiting */
        .electrons-orbit-group {
            position: absolute;
            inset: 0;
            animation: orbitGroup calc(var(--shell-speed) * 1.5) linear infinite;
        }

        @keyframes orbitGroup {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .electron-orbit-wrapper {
            position: absolute;
            width: 100%;
            height: 100%;
            transform: rotate(var(--orbit-angle));
        }

        .electron-dot {
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            top: 0;
            left: 50%;
            margin-left: -6px;
            z-index: 10;
            filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.3));
        }

        .electron-dot-inner {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.9),
                        0 0 20px rgba(59, 130, 246, 0.5),
                        inset 0 1px 3px rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .electron-dot-valence {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 0 12px rgba(245, 158, 11, 1),
                        0 0 24px rgba(245, 158, 11, 0.6),
                        inset 0 1px 3px rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>

@endsection
