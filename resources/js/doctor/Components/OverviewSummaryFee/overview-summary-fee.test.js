/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import OverviewSummaryFeeComponent from "./OverviewSummaryFee.vue";

describe("OverviewSummaryFeeComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(OverviewSummaryFeeComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
