/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import AppointmentPage from "./Appointment.vue";

describe("AppointmentPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(AppointmentPage);
        expect(wrapper.exists()).toBe(true);
    });
});
