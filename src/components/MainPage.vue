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
                   @click="setTab('3')">Результат HTML</a>
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
                        <textarea v-model="text" class="form-control"cols="100" rows="6"
                                  placeholder="Вставьте текст с фразами для генерации объявлений. Каждая фраза с новой строки. После введите ключевые слова для заголовков и намите кнопку сгенерировать"></textarea>

                    </div>
                    <div class="col-3 col-lg-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-primary" @click="generateHeaders">
                                    получить Заголовок 1
                                </button>
                            </div>
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-primary" @click="generateSecondHeaders">
                                    получить Заголовок 2
                                </button>
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
                        <div class="row" v-for="(header_word) in header1">
                            <div class="col-lg-11">
                                <input type="text" v-model="header_word.value" class="custom-text"
                                       :class="{danger: getLength(header_word.value)>35}"/>
                            </div>
                            <div class="col-lg-1">
                                <span> {{ getLength(header_word.value) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <h6>Заголовок 2 (max 53)</h6>
                        <div class="row" v-for="(header2_word, key) in header2">
                            <div class="col-lg-11">
                                <input type="text" v-model="header2_word.value" class="custom-text"
                                       :class="{danger: (getLength(header1[key].value)+getLength(header2_word.value))>53}"/>
                            </div>
                            <div class="col-lg-1">
                                <span> {{ (getLength(header1[key].value)+getLength(header2_word.value)) }}</span>
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
                        <label>Текст ({{ getLength(info_text) }})</label>
                        <textarea v-model="info_text" class="form-control" :class="{danger: getLength(info_text)>81}" placeholder="Дополнительный текст не более 81 символа"></textarea>
                    </div>
                    <div class="col-2 col-lg-2">
                        <br/>
                        <button type="button" class="btn btn-success" @click="generateHTML">экспорт в HTML</button><br/><br/>
                    </div>
                </div>
            </div>
            <div class="tab-pane" :class="{active : current_tab==3}">
                <div class="col-lg-12 text-right"> <button type="button" class="btn btn-danger" @click="exportExcel">экспорт Excel</button></div>
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

    import axios from 'axios';

    export default {
        data: function () {
            return {
                words: [
                    {'id': '0', 'value': 'купить', 'count': ''},
                    {'id': '1', 'value': 'акция', 'count': ''},
                    {'id': '2', 'value': '25!', 'count': ''},
                    {'id': '3', 'value': '', 'count': ''},
                    {'id': '4', 'value': '', 'count': ''}
                    ],
                second_words: [
                    {'id': '0', 'value': 'даром', 'count': ''},
                    {'id': '1', 'value': '13!', 'count': ''},
                    {'id': '2', 'value': 'бесплатно', 'count': ''},
                    {'id': '3', 'value': '', 'count': ''},
                    {'id': '4', 'value': '', 'count': ''}],
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
                result_html: [],
                length_header1: 35,
                length_header2: 53
            }
        },
        created() {
            // for (let i = 0; i <= this.count_header; i++) {
            //     this.minus_words.push({'value': ''});
            //     this.header1.push({'value': ''});
            //     this.header2.push({'value': ''});
            // }
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
            getLength(val) {
                val = val.replace(/\s+/g,' ');
                val = val.replace(/\++/g,' ');
                val = val.replace(/\_+/g,' ');
                val = val.replace(/\%+/g,'');
                val = val.replace(/\!+/g,'');
                val = val.replace(/\?+/g,'');
                val = val.replace(/\#+/g,'');
                val = val.replace(/\"+/g,'');
                val = val.replace(/\$+/g,'');
                val = val.replace(/\,+/g,'');
                val = val.replace(/\.+/g,'');
                return val.length;
            },
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
                    return {'value': this.getWord(item.value, this.words, this.length_header1)};
                });
                // console.log(this.header1);

                // this.header2 = this.header1.map((item, i) => {
                //     return {'value': this.getSecondWord()};
                // });
            },
            generateSecondHeaders: function () {
                console.log('generate headers2');
                this.process = true;
                this.header2 = this.header1.map((item, i) => {
                    return {'value': this.getSecondWord(item.value, this.second_words, this.length_header2)};
                });
            },
            getWord(word, words, max_length) {
                let result = ''
                if (word != '') {
                    word = word.charAt(0).toUpperCase() + word.substr(1);
                    word = word.replace(/\++/g,' ');
                    word = word.replace(/\_+/g,' ');
                    let temp_words = Object.assign(words);
                    temp_words = temp_words.filter(function (val) {
                        // console.log('current word', val);
                        return val.value.length>0;
                    });
                    // console.log('temp words', temp_words);

                    let count = temp_words.length;
                    for(let i=0; i< count; i++) {
                        let max_word = temp_words.reduce(function (item, current) {
                            // console.log('item', item, current);
                            if (item.value.length < current.value.length)
                                item = current;
                            return item;
                        });
                        result = word + ' ' + max_word.value;
                        console.log(word, 'max word', max_word.value,'result', result.length);
                        if (result.length>=max_length) {
                            temp_words = temp_words.filter(function (val) {
                                return val.value!=max_word.value;
                            });
                            // console.log('delete long word',max_word.value, temp_words);
                            // console.log('temp_words', temp_words);
                        }
                        else {
                            break;
                        }
                    }
                    // result = word + ' ' + this.words[this.randomInteger(0, 4)].value;
                }
                return result
            },
            getSecondWord(word, words, max_length) {
                let result = ''
                if (word != '') {
                    word = word.charAt(0).toUpperCase() + word.substr(1);
                    word = word.replace(/\++/g,' ');
                    word = word.replace(/\_+/g,' ');
                    let temp_words = Object.assign(words);
                    temp_words = temp_words.filter(function (val) {
                        // console.log('current word', val);
                        return val.value.length>0;
                    });
                    // console.log('temp words', temp_words);

                    let count = temp_words.length;
                    for(let i=0; i< count; i++) {
                        let max_word = temp_words.reduce(function (item, current) {
                            // console.log('item', item, current);
                            if (item.value.length < current.value.length)
                                item = current;
                            return item;
                        });
                        result = max_word.value;
                        console.log(word, 'max word', max_word.value,'result', result.length);
                        if ((word + ' ' + max_word.value).length>=max_length) {
                            temp_words = temp_words.filter(function (val) {
                                return val.value!=max_word.value;
                            });
                            result = max_word.value;
                            // console.log('delete long word',max_word.value, temp_words);
                            // console.log('temp_words', temp_words);
                        }
                        else {
                            break;
                        }
                    }
                    // result = word + ' ' + this.words[this.randomInteger(0, 4)].value;
                }
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
                });
                this.current_tab = 3;
                this.exportExcel();
            },
            exportExcel() {
                console.log('Start export data to Excel');

                axios.post('/load_data.php', {
                    data: JSON.stringify(this.result_html)
                }).then(response => {
                    console.log('response', response.data);
                    let data = response.data;
                    console.log('data', data,data.filename);
                    if (data.filename != '') {
                        window.open('/files/'+data.filename,'_blank');
                    }
                }).catch(function (error) {
                    console.log('error load  export data', error);
                });
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
