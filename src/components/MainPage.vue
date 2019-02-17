<template>
    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_calc" :class="{active : current_tab==1}"
                   @click="setTab('1')">Данные</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_ext" :class="{active : current_tab==2}"
                   @click="setTab('2')">Дополнительные параметры</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_ext" :class="{active : current_tab==3}"
                   @click="setTab('3')">Результат</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" :class="{active : current_tab==1}">

                <div class="row form-group grey">
                    <div class="col-3 col-lg-3">
                        <h6>Ключевые слова для Заголовка 1</h6>
                        <div class="row" v-for="(word) in words">
                            <div class="col-11 col-lg-11">
                                <input type="text" v-model="word.value" class="custom-text"/>
                            </div>
                            <div class="col-1 col-lg-1">
                                <span> {{ word.value.length }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-lg-3">
                        <h6>Ключевые слова для Заголовка 2</h6>
                        <div class="row" v-for="(word2) in second_words">
                            <div class="col-11 col-lg-11">
                                <input type="text" v-model="word2.value" class="custom-text"/>
                            </div>
                            <div class="col-1 col-lg-1">
                                <span> {{ word2.value.length }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-lg-3">
                        <h6>Данные</h6>
                        <textarea v-model="text" class="form-control"
                                  placeholder="Вставьте текст с фразами для генерации объявлений. Каждая фраза с новой строки. После введите ключевые слова для заголовков и намите кнопку сгенерировать"></textarea>

                    </div>
                    <div class="col-3 col-lg-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-primary" @click="generateHeaders">сгенерировать
                                </button>
                            </div>
                            <div class="col-lg-6">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <h6>Заголовок-минус</h6>
                        <div class="row" v-for="(minus_word) in minus_words">
                            <div class="col-lg-11">
                                <input type="text" v-model="minus_word.value" class="custom-text"/>
                            </div>
                            <div class="col-lg-1">
                                <span> {{ minus_word.value.length }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h6>Заголовок 1 (max 35)</h6>
                        <div class="row" v-for="(header_word, key) in header1">
                            <div class="col-lg-11">
                                <input type="text" v-model="header_word.value" class="custom-text"
                                       :class="{danger: header_word.value.length>35}" @change="test(header_word.value, key)"/>
                            </div>
                            <div class="col-lg-1">
                                <span> {{ header_word.value.length }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <h6>Заголовок 2 (max 53)</h6>
                        <div class="row" v-for="(header2_word) in header2">
                            <div class="col-lg-11">
                                <input type="text" v-model="header2_word.value" class="custom-text"
                                       :class="{danger: header2_word.value.length>53}"/>
                            </div>
                            <div class="col-lg-1">
                                <span> {{ header2_word.value.length }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" :class="{active : current_tab==2}">
                <div class="row">
                    <div class="col-2 col-lg-2">
                        <label>URL сайта</label>
                        <input type="text" v-model="url" class="form-control" placeholder="http://icosmetics.ru/"/>
                        <label>Группа</label>
                        <input type="text" v-model="group_name" class="form-control" placeholder="название группы"/>
                    </div>
                    <div class="col-4 col-lg-4">
                        <label>Метка</label>
                        <input type="text" v-model="s1" class="form-control"/>
                        <label>A1</label>
                        <input type="text" v-model="a1" class="form-control"placeholder="хвост к метке"/>
                    </div>
                    <div class="col-4 col-lg-4">
                        <label>Текст ({{ info_text.length }})</label>
                        <textarea v-model="info_text" class="form-control" :class="{danger: info_text.length>81}" placeholder="Дополнительный текст не более 81 символа"></textarea>
                    </div>
                    <div class="col-2 col-lg-2">
                        <br/>
                        <button type="button" class="btn btn-success" @click="generateHTML">экспорт в HTML</button><br/><br/>
                        <button type="button" class="btn btn-danger">экспорт в Excel</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane" :class="{active : current_tab==3}">
                <h3>Предложение текстовых блоков для рекламной кампании</h3>
                <table border="1">
                    <tr v-for="(item, key) in result_html">
                        <td>{{ key+1 }}</td>
                        <td>{{ item.name_group }}</td>
                        <td>{{ item.minus_word }}</td>
                        <td>{{ item.header1 }}</td>
                        <td>{{ item.header2 }}</td>
                        <td>{{ item.info_text }}</td>
                        <td>{{ item.url }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                words: [{'value': 'первое', 'count': ''}, {'value': 'второе', 'count': ''}, {
                    'value': 'третье',
                    'count': ''
                }, {'value': 'четвертое', 'count': ''}, {'value': 'пятое', 'count': ''}],
                second_words: [{'value': 'первое', 'count': ''}, {'value': 'второе', 'count': ''}, {
                    'value': 'третье',
                    'count': ''
                }, {'value': 'четвертое', 'count': ''}, {'value': 'пятое', 'count': ''}],
                minus_words: [],
                header1: [],
                header2: [],
                process: false,
                count_header: 10,
                count_word: 5,
                file: '',
                text: '',
                current_tab: 1,
                group_name: '',
                info_text: '',
                url: '',
                s1: '?utm_source=Yandex&utm_medium=Direct&utm_term={Keyword}&utm_content=Poisk&utm_campaign=',
                a1: '',
                group_name: '',
                result_html: []
            }
        },
        created() {
            for (let i = 0; i <= this.count_header; i++) {
                this.minus_words.push({'value': ''});
                this.header1.push({'value': ''});
                this.header2.push({'value': ''});
            }
        },
        watch: {
            count_header: function (val) {
                this.minus_words = [];
                this.header1 = [];
                this.header2 = [];
                for (let i = 0; i < val; i++) {
                    this.minus_words.push({'value': ''});
                    this.header1.push({'value': ''});
                    this.header2.push({'value': ''});
                }
                this.generateHeaders();
            },
            text: function (val) {
                this.minus_words = [];
                this.header1 = [];
                this.header2 = [];
                let arr = val.split(/\r?\n+/);
                for (let i = 0; i < arr.length; i++) {
                    this.minus_words.push({'value': arr[i]});
                    this.header1.push({'value': ''});
                    this.header2.push({'value': ''});
                }
            },
        },
        methods: {
            test(val, key) {
                console.log('change header 2', key, val);
                this.header2[key].value = val +' '+ this.second_words[this.randomInteger(0, 4)].value;
                console.log(this.header2[key]);
            },
            setTab: function (id) {
                this.current_tab = id;
            },
            generateHeaders: function () {
                this.process = true;
                // this.minus_words.forEach(function (item, i) {
                //     console.log('val', item, i);
                // })
                this.header1 = this.minus_words.map((item, i) => {
                    return {'value': this.getWord(item.value)};
                });
                console.log(this.header1);

                this.header2 = this.header1.map((item, i) => {
                    return {'value': this.getSecondWord(item.value)};
                });
            },
            getWord(word) {
                let result = ''
                if (word != '') {
                    word = word.charAt(0).toUpperCase() + word.substr(1);
                    word = word.replace(/\+/g,' ');
                    word = word.replace(/\_/g,' ');
                    result = word + ' ' + this.words[this.randomInteger(0, 4)].value;
                }
                return result
            },
            getSecondWord(word) {
                let result = ''
                if (word != '')
                    result = word + ' ' + this.second_words[this.randomInteger(0, 4)].value;
                return result
            },
            randomInteger(min, max) {
                let rand = min - 0.5 + Math.random() * (max - min + 1);
                rand = Math.round(rand);
                return rand;
            },
            handleFileUpload() {
                console.log('file', this.$refs.file.files[0]);
                this.file = this.$refs.file.files[0];
                console.log('file', this.$refs.file.files[0]);
            },
            generateHTML() {
                this.result_html = [];
                this.minus_words.forEach((item, i) => {
                    console.log('i',this.header1[i].value, this.header1[i]);
                    this.result_html.push(
                        {'name_group': this.group_name,
                        'minus_word': item.value,
                        'header1': this.header1[i].value,
                        'header2': this.header2[i].value,
                        'info_text': this.info_text,
                        'url': this.url+this.s1+this.a1}
                        );
                })
            }

        }
    }
</script>

<style scoped>
    .danger {
        background-color: #cc7f91;
    }

    h6 {
        font-weight: 700;
    }

    .grey {
        /*background-color: powderblue;*/
    }

    .custom-text {
        font-size: 15px;
        padding: 5px;
        height: 27px;
        width: 100%;
        color: #111115;

    }

    textarea {
        height: 190px;
    }
    table {
        width: 100%;
        font-size: 10px;
    }
</style>
