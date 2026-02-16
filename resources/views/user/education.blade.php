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
                <template x-for="m in ['Hub','Library','AI Lab']" :key="m">
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

        <!-- LIBRARY -->
        <div x-show="mode === 'Library'" class="space-y-14">

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
                                    <p class="text-base sm:text-lg text-amber-200 font-bold italic" x-text="q.correct_answer"></p>
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
                    // Convert letter answer (A, B, C, D) to index (0, 1, 2, 3)
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

@endsection
