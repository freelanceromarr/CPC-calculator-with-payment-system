<template>
	<jet-authentication-card>
		<template #logo> </template>

		<jet-validation-errors class="mb-4" />

		<form @submit.prevent="submit">
			<div>
				<jet-label for="name" value="Name" />
				<jet-input
					id="name"
					type="text"
					class="mt-1 block w-full"
					v-model="form.name"
					required
					autofocus
					autocomplete="name"
				/>
			</div>

			<div class="mt-4">
				<jet-label for="email" value="Email" />
				<jet-input
					id="email"
					type="email"
					class="mt-1 block w-full"
					v-model="form.email"
					required
				/>
			</div>

			<div class="mt-4">
				<jet-label for="Country" value="Country" />
				<select
					class="
						mt-1
						block
						w-full
						border-gray-300
						focus:border-indigo-300
						focus:ring focus:ring-indigo-200 focus:ring-opacity-50
						rounded-md
						shadow-sm
					"
					v-model="form.countrySelected"
				>
					<option value="">Select Country</option>
					<option
						v-bind:value="country.name"
						v-for="country in contryList.countries"
						:key="country.name"
					>
						{{ country.name }}
					</option>
				</select>
			</div>
			<div class="mt-4">
				<jet-label for="Phone" value="Phone" />
				<jet-input
					id="phone"
					type="text"
					class="mt-1 block w-full"
					v-model="form.phone"
					required
				/>
                {{form.phone}}
			</div>

			<div class="mt-4">
				<jet-label for="password" value="Password" />
				<jet-input
					id="password"
					type="password"
					class="mt-1 block w-full"
					v-model="form.password"
					required
					autocomplete="new-password"
				/>
			</div>

			<div class="mt-4">
				<jet-label for="password_confirmation" value="Confirm Password" />
				<jet-input
					id="password_confirmation"
					type="password"
					class="mt-1 block w-full"
					v-model="form.password_confirmation"
					required
					autocomplete="new-password"
				/>
			</div>

			<!-- stripe form start -->
			<div class="mt-4">
				<jet-label for="Card information" value="Card information" />
				<div id="card-element"><!--Stripe.js injects the Card Element--></div>
				<!-- <button>
				<div class="spinner hidden" id="spinner"></div> -->
				<span id="button-text">Pay $1.99</span>
				<!-- </button> -->
				<p class="text-red-700" id="card-error" role="alert">{{ error }}</p>
				<p class="result-message hidden">
					Payment succeeded, see the result in your
					<a href="" target="_blank">Stripe dashboard.</a> Refresh the page to
					pay again.
				</p>
			</div>
			<!-- stripe form end -->
			<div
				class="mt-4"
				v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
			>
				<jet-label for="terms">
					<div class="flex items-center">
						<jet-checkbox
							name="terms"
							id="terms"
							v-model:checked="form.terms"
						/>

						<div class="ml-2">
							I agree to the
							<a
								target="_blank"
								:href="route('terms.show')"
								class="underline text-sm text-gray-600 hover:text-gray-900"
								>Terms of Service</a
							>
							and
							<a
								target="_blank"
								:href="route('policy.show')"
								class="underline text-sm text-gray-600 hover:text-gray-900"
								>Privacy Policy</a
							>
						</div>
					</div>
				</jet-label>
			</div>

			<div class="flex items-center justify-end mt-4">
				<inertia-link
					:href="route('login')"
					class="underline text-sm text-gray-600 hover:text-gray-900"
				>
					Already registered?
				</inertia-link>

				<jet-button
					class="ml-4"
					:class="{ 'opacity-25': form.processing }"
					:disabled="form.processing"
				>
					Pay & Register
				</jet-button>
			</div>
		</form>
	</jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import { loadStripe } from "@stripe/stripe-js";
import countries from "../../country.js";

// stirpe js start

//stripe js end

export default {
	components: {
		JetAuthenticationCard,
		JetAuthenticationCardLogo,
		JetButton,
		JetInput,
		JetCheckbox,
		JetLabel,
		JetValidationErrors,
	},

	data() {
		return {
			paymentProcessing: false,
			stripe: {},
			cardElement: {},
			contryList: countries,
			form: this.$inertia.form({
				name: "",
				email: "",
				countrySelected: "",
                phone: "",
				password: "",
				password_confirmation: "",
				payment_method_id: "",
				amount: "",
				transition_id: "",
				error: "",
				terms: false,
			}),
		};
	},
	created() {},
	async mounted() {
		this.stripe = await loadStripe(
			"pk_test_51H5H43KtsS9B0VSvdRxEWqfLlkKsW9uVFzo1UBh9Fpr0784yhnotEvf0ou4hCQnfm7IPIgrfSksGe5SGveqX6UXj00y5U8jDsu"
		);
		const elements = this.stripe.elements();
		this.cardElement = elements.create("card", {
			classes: {
				base: "bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 p-3 leading-8 transition-colors duration-200 ease-in-out",
			},
		});
		this.cardElement.mount("#card-element");
	},
	methods: {
		onSelcetCountry() {
			console.log();
		},
		// form submit function
		async submit() {
			this.paymentProcessing = true;

			const { paymentMethod, error } = await this.stripe.createPaymentMethod({
				type: "card",
				card: this.cardElement,
				billing_details: { name: "Jenny Rosen" },
			});

			if (error) {
				this.paymentProcessing = false;
				this.error = error.message;
			} else {
				console.log(paymentMethod);
				this.form.payment_method_id = paymentMethod.id;
				this.form.amount = Math.round(1.99 * 100);
				console.log(this.form);
				this.form.post(this.route("register"), {
					onFinish: () => this.form.reset("password", "password_confirmation"),
				});
			}
		},
	},
};
</script>
