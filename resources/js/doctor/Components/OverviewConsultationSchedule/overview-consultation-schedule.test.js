/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import OverviewConsultationScheduleComponent from "./OverviewConsultationSchedule.vue";

describe("OverviewConsultationScheduleComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(OverviewConsultationScheduleComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
