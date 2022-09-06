<template>
  <div style="position: relative">
    <div
      style="
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, 50%);
      "
    >
      <v-form ref="form">
        <v-card width="400px">
          <v-row align="center" justify="center" class="py-5">
            <h1>Đăng nhập</h1>
          </v-row>
          <v-card-actions>
            <v-text-field
              label="Tài khoản"
              v-model="username"
              :rules="rules"
            ></v-text-field>
          </v-card-actions>
          <v-card-actions>
            <v-text-field
              label="Mật khẩu"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              append-icon="mdi-eye"
              :rules="rules"
              @click:append="showPassword = !showPassword"
              @keyup.enter="login"
            ></v-text-field>
          </v-card-actions>
          <v-row align="center" justify="center" class="py-5">
            <v-btn :loading="loading" @click="login" color="primary lighten-1">Đăng nhập</v-btn>
          </v-row>
          <v-row class="ma-0">
            <v-btn text @click="$router.push({ name: 'homepage' })">
              <v-icon>mdi-back</v-icon>
              <span>Trở lại trang chủ</span>
            </v-btn>
          </v-row>
        </v-card>
      </v-form>
    </div>
  </div>
</template>

<script>
import AppService from "@/services/app.service";

export default {
  data() {
    return {
      username: '',
      password: '',
      rules: [
        // (v) => v ? v.trim().length >= 5 ? true : "Tối thiểu 5 ký tự" : "Không được để trống"
        v => !!v || 'Không được để trống',
        v => v.trim().length >= 5 || 'Tối thiểu 5 ký tự',
        v => v.trim().length <= 20 || 'Tối đa 20 ký tự'
      ],
      loading: false,
      showPassword: false
    };
  },
  methods: {
    async login() {
      if (this.$refs["form"].validate()) {
        this.loading = true
        await AppService.login({
          username: this.username,
          password: this.password,
        })
          .then((res) => {
            localStorage.setItem("myblog_token", res.data.access_token);
            window.location.href = '/'
          })
          .catch((res) => {
            this.$emit("toastMessage", res.response.data.message, true);
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
  },
};
</script>